<?php

namespace App\Http\Controllers\Sites\Public;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Show the checkout page with Stripe payment form
     */
    public function show(Order $order)
    {
        // Verify order is pending payment
        if ($order->status !== 'pending') {
            return redirect()->route('booking.confirmation', $order->items->first()->orderable->confirmation_token)
                ->with('message', 'This order has already been processed');
        }

        // Load relationships
        $order->load(['items.orderable', 'company']);

        return Inertia::render('Sites/Public/Checkout/Index', [
            'order' => $order,
            'stripeKey' => config('cashier.key'),
        ]);
    }

    /**
     * Create a payment intent for the order
     */
    public function createPaymentIntent(Order $order)
    {
        try {
            if ($order->status !== 'pending') {
                return response()->json([
                    'error' => 'This order has already been processed',
                ], 422);
            }

            // Mock mode for development (skip Stripe)
            if (config('app.env') === 'local' && request()->header('X-Mock-Payment')) {
                return response()->json([
                    'clientSecret' => 'mock_client_secret_' . $order->id,
                    'paymentIntentId' => 'mock_pi_' . \Illuminate\Support\Str::random(24),
                    'mock' => true,
                ]);
            }

            // Check if Stripe keys are configured
            if (!config('cashier.secret') || !config('cashier.key')) {
                \Log::error('Stripe keys not configured');
                return response()->json([
                    'error' => 'Payment system not configured. Please contact support.',
                ], 500);
            }

            $paymentIntent = $this->paymentService->createPaymentIntent($order);

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
                'paymentIntentId' => $paymentIntent->id,
            ]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            \Log::error('Stripe API Error', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'type' => get_class($e),
            ]);
            return response()->json([
                'error' => 'Payment service error: ' . $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            \Log::error('Payment Intent Creation Error', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'error' => 'Failed to create payment intent: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Confirm payment and complete order
     */
    public function confirm(Request $request, Order $order)
    {
        $request->validate([
            'payment_intent_id' => 'required|string',
        ]);

        try {
            $paymentIntentId = $request->input('payment_intent_id');

            // Mock mode for development (skip Stripe)
            if (config('app.env') === 'local' && str_starts_with($paymentIntentId, 'mock_pi_')) {
                // Simulate payment processing
                $mockSuccess = $request->input('mock_success', true);

                if ($mockSuccess) {
                    // Create mock payment record
                    $payment = \App\Models\Payment::create([
                        'order_id' => $order->id,
                        'payment_gateway' => 'mock',
                        'gateway_transaction_id' => $paymentIntentId,
                        'amount' => $order->total,
                        'currency' => $order->currency,
                        'status' => 'succeeded',
                        'processed_at' => now(),
                        'metadata' => [
                            'payment_intent_id' => $paymentIntentId,
                            'mock' => true,
                        ],
                    ]);

                    // Update order status
                    $order->markAsPaid();

                    // Update booking status and send confirmation emails
                    foreach ($order->items as $item) {
                        if ($item->orderable_type === 'App\\Models\\Booking') {
                            $booking = $item->orderable;
                            if ($booking && $booking->status === 'pending_payment') {
                                $booking->update(['status' => 'confirmed']);

                                // Send booking confirmation email
                                $booking->load(['company', 'staff']);
                                \Illuminate\Support\Facades\Mail::to($booking->customer_email)
                                    ->queue(new \App\Mail\BookingConfirmation($booking));

                                \Log::info('Booking confirmation email queued', [
                                    'booking_id' => $booking->id,
                                    'customer_email' => $booking->customer_email,
                                ]);
                            }
                        }
                    }

                    // Send payment confirmation email
                    $payment->load('order');
                    \Illuminate\Support\Facades\Mail::to($order->customer_email)
                        ->queue(new \App\Mail\PaymentConfirmation($payment));

                    \Log::info('Payment confirmation email queued', [
                        'order_id' => $order->id,
                        'customer_email' => $order->customer_email,
                    ]);

                    // Send order receipt email
                    $order->load(['items', 'company', 'payment']);
                    \Illuminate\Support\Facades\Mail::to($order->customer_email)
                        ->queue(new \App\Mail\OrderReceipt($order));

                    \Log::info('Order receipt email queued', [
                        'order_id' => $order->id,
                        'customer_email' => $order->customer_email,
                    ]);

                    // Get booking for redirect
                    $booking = $order->items->first()->orderable;

                    return response()->json([
                        'success' => true,
                        'redirect_url' => route('booking.confirmation', $booking->confirmation_token),
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Mock payment failed',
                    ], 422);
                }
            }

            // Real Stripe payment processing
            $paymentIntent = $this->paymentService->retrievePaymentIntent($paymentIntentId);

            if ($paymentIntent->status === 'succeeded') {
                // Handle successful payment
                $this->paymentService->handleSuccessfulPayment($paymentIntentId);

                // Get booking for redirect
                $booking = $order->items->first()->orderable;

                return response()->json([
                    'success' => true,
                    'redirect_url' => route('booking.confirmation', $booking->confirmation_token),
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Payment not completed',
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Payment confirmation failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}
