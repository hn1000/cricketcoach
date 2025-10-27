<?php

namespace App\Services;

use App\Mail\BookingConfirmation;
use App\Mail\OrderReceipt;
use App\Mail\PaymentConfirmation;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Refund;
use Stripe\Stripe;

class PaymentService
{
    public function __construct()
    {
        Stripe::setApiKey(config('cashier.secret'));
    }

    /**
     * Create a payment intent for an order
     */
    public function createPaymentIntent(Order $order, array $metadata = []): PaymentIntent
    {
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $this->convertToStripeAmount($order->total, $order->currency),
                'currency' => strtolower($order->currency),
                'description' => "Order {$order->order_number} - {$order->company->name}",
                'metadata' => array_merge([
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'company_id' => $order->company_id,
                    'customer_email' => $order->customer_email,
                ], $metadata),
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            // Create pending payment record
            Payment::create([
                'order_id' => $order->id,
                'payment_gateway' => 'stripe',
                'gateway_transaction_id' => $paymentIntent->id,
                'amount' => $order->total,
                'currency' => $order->currency,
                'status' => 'pending',
                'metadata' => [
                    'payment_intent_id' => $paymentIntent->id,
                    'client_secret' => $paymentIntent->client_secret,
                ],
            ]);

            return $paymentIntent;
        } catch (ApiErrorException $e) {
            Log::error('Stripe Payment Intent Creation Failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Handle successful payment
     */
    public function handleSuccessfulPayment(string $paymentIntentId): void
    {
        try {
            $payment = Payment::where('gateway_transaction_id', $paymentIntentId)->firstOrFail();
            $order = $payment->order;

            // Update payment status
            $payment->update([
                'status' => 'succeeded',
                'processed_at' => now(),
            ]);

            // Update order status
            $order->markAsPaid();

            // Update related bookings to confirmed status
            foreach ($order->items as $item) {
                if ($item->orderable_type === 'App\\Models\\Booking') {
                    $booking = $item->orderable;
                    if ($booking && $booking->status === 'pending_payment') {
                        $booking->update(['status' => 'confirmed']);

                        // Send booking confirmation email
                        $booking->load(['company', 'staff']);
                        Mail::to($booking->customer_email)->queue(new BookingConfirmation($booking));
                    }
                }
            }

            // Send payment confirmation email
            $payment->load('order');
            Mail::to($order->customer_email)->queue(new PaymentConfirmation($payment));

            // Send order receipt email
            $order->load(['items', 'company', 'payment']);
            Mail::to($order->customer_email)->queue(new OrderReceipt($order));

            Log::info('Payment processed successfully', [
                'payment_intent_id' => $paymentIntentId,
                'order_id' => $order->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to handle successful payment', [
                'payment_intent_id' => $paymentIntentId,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Handle failed payment
     */
    public function handleFailedPayment(string $paymentIntentId, string $failureReason = null): void
    {
        try {
            $payment = Payment::where('gateway_transaction_id', $paymentIntentId)->first();

            if ($payment) {
                $payment->update([
                    'status' => 'failed',
                    'processed_at' => now(),
                    'metadata' => array_merge($payment->metadata ?? [], [
                        'failure_reason' => $failureReason,
                    ]),
                ]);

                Log::warning('Payment failed', [
                    'payment_intent_id' => $paymentIntentId,
                    'order_id' => $payment->order_id,
                    'reason' => $failureReason,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to handle failed payment', [
                'payment_intent_id' => $paymentIntentId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Process a refund for an order
     */
    public function refundOrder(Order $order, float $amount = null, string $reason = null): Refund
    {
        try {
            $successfulPayment = $order->successfulPayment();

            if (!$successfulPayment) {
                throw new \Exception('No successful payment found for this order');
            }

            $refundAmount = $amount ?? $order->total;

            $refund = Refund::create([
                'payment_intent' => $successfulPayment->gateway_transaction_id,
                'amount' => $this->convertToStripeAmount($refundAmount, $order->currency),
                'reason' => $reason ?? 'requested_by_customer',
                'metadata' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                ],
            ]);

            // Create refund payment record
            Payment::create([
                'order_id' => $order->id,
                'payment_gateway' => 'stripe',
                'gateway_transaction_id' => $refund->id,
                'amount' => -$refundAmount, // Negative amount for refund
                'currency' => $order->currency,
                'status' => 'refunded',
                'payment_method' => 'refund',
                'processed_at' => now(),
                'metadata' => [
                    'refund_id' => $refund->id,
                    'original_payment_intent' => $successfulPayment->gateway_transaction_id,
                    'reason' => $reason,
                ],
            ]);

            // Update order status
            if ($refundAmount >= $order->total) {
                $order->update(['status' => 'refunded']);
            }

            Log::info('Refund processed successfully', [
                'order_id' => $order->id,
                'refund_id' => $refund->id,
                'amount' => $refundAmount,
            ]);

            return $refund;
        } catch (ApiErrorException $e) {
            Log::error('Stripe Refund Failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Retrieve a payment intent from Stripe
     */
    public function retrievePaymentIntent(string $paymentIntentId): PaymentIntent
    {
        try {
            return PaymentIntent::retrieve($paymentIntentId);
        } catch (ApiErrorException $e) {
            Log::error('Failed to retrieve payment intent', [
                'payment_intent_id' => $paymentIntentId,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Convert amount to Stripe format (cents)
     */
    protected function convertToStripeAmount(float $amount, string $currency): int
    {
        // Zero-decimal currencies (e.g., JPY, KRW) don't need conversion
        $zeroDecimalCurrencies = ['BIF', 'CLP', 'DJF', 'GNF', 'JPY', 'KMF', 'KRW', 'MGA', 'PYG', 'RWF', 'UGX', 'VND', 'VUV', 'XAF', 'XOF', 'XPF'];

        if (in_array(strtoupper($currency), $zeroDecimalCurrencies)) {
            return (int) round($amount);
        }

        return (int) round($amount * 100);
    }

    /**
     * Convert amount from Stripe format to decimal
     */
    public function convertFromStripeAmount(int $amount, string $currency): float
    {
        $zeroDecimalCurrencies = ['BIF', 'CLP', 'DJF', 'GNF', 'JPY', 'KMF', 'KRW', 'MGA', 'PYG', 'RWF', 'UGX', 'VND', 'VUV', 'XAF', 'XOF', 'XPF'];

        if (in_array(strtoupper($currency), $zeroDecimalCurrencies)) {
            return (float) $amount;
        }

        return $amount / 100;
    }
}
