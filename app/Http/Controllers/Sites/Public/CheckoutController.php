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

            $paymentIntent = $this->paymentService->createPaymentIntent($order);

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
                'paymentIntentId' => $paymentIntent->id,
            ]);
        } catch (\Exception $e) {
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

            // Retrieve payment intent from Stripe to verify status
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
