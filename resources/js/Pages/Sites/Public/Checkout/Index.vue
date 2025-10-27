<script setup>
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { loadStripe } from '@stripe/stripe-js';

const props = defineProps({
    order: Object,
    stripeKey: String,
});

const stripe = ref(null);
const elements = ref(null);
const cardElement = ref(null);
const processing = ref(false);
const error = ref(null);
const paymentIntentId = ref(null);

onMounted(async () => {
    // Load Stripe
    stripe.value = await loadStripe(props.stripeKey);

    // Create payment intent
    await createPaymentIntent();

    // Setup Stripe Elements
    if (stripe.value && paymentIntentId.value) {
        elements.value = stripe.value.elements({
            clientSecret: clientSecret.value,
        });

        cardElement.value = elements.value.create('payment', {
            layout: 'tabs',
        });

        cardElement.value.mount('#card-element');
    }
});

const clientSecret = ref(null);

const createPaymentIntent = async () => {
    try {
        const response = await fetch(route('checkout.payment-intent', props.order.id), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
        });

        const data = await response.json();

        if (response.ok) {
            clientSecret.value = data.clientSecret;
            paymentIntentId.value = data.paymentIntentId;
        } else {
            error.value = data.error || 'Failed to initialize payment';
        }
    } catch (err) {
        error.value = 'Failed to connect to payment service';
    }
};

const handleSubmit = async () => {
    if (!stripe.value || !elements.value || processing.value) {
        return;
    }

    processing.value = true;
    error.value = null;

    try {
        // Confirm payment with Stripe
        const { error: stripeError } = await stripe.value.confirmPayment({
            elements: elements.value,
            confirmParams: {
                return_url: window.location.origin + '/payment-processing',
            },
            redirect: 'if_required',
        });

        if (stripeError) {
            error.value = stripeError.message;
            processing.value = false;
            return;
        }

        // Payment successful, confirm with backend
        const response = await fetch(route('checkout.confirm', props.order.id), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                payment_intent_id: paymentIntentId.value,
            }),
        });

        const data = await response.json();

        if (data.success) {
            window.location.href = data.redirect_url;
        } else {
            error.value = data.error || 'Payment confirmation failed';
            processing.value = false;
        }
    } catch (err) {
        error.value = 'An unexpected error occurred';
        processing.value = false;
    }
};

// Get booking details from order
const booking = props.order.items[0]?.orderable;
const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};
</script>

<template>
    <Head title="Checkout" />

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Complete Your Booking</h1>
                <p class="mt-2 text-gray-600">Enter your payment details to confirm your appointment</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Order Summary (Left Side) -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h2>

                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-600">Company</p>
                                <p class="font-medium text-gray-900">{{ order.company.name }}</p>
                            </div>

                            <div v-if="booking">
                                <p class="text-sm text-gray-600">Date & Time</p>
                                <p class="font-medium text-gray-900">{{ formatDate(booking.booking_date) }}</p>
                                <p class="text-gray-700">{{ booking.start_time.substring(0, 5) }} - {{ booking.end_time.substring(0, 5) }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-600">Customer</p>
                                <p class="font-medium text-gray-900">{{ order.customer_name }}</p>
                                <p class="text-sm text-gray-600">{{ order.customer_email }}</p>
                            </div>

                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex justify-between mb-2">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="text-gray-900">${{ order.subtotal }}</span>
                                </div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-gray-600">Tax</span>
                                    <span class="text-gray-900">${{ order.tax }}</span>
                                </div>
                                <div class="flex justify-between text-lg font-semibold border-t border-gray-200 pt-2">
                                    <span class="text-gray-900">Total</span>
                                    <span class="text-blue-600">${{ order.total }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Form (Right Side) -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6">Payment Information</h2>

                        <!-- Error Message -->
                        <div v-if="error" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex">
                                <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-sm text-red-800">{{ error }}</p>
                            </div>
                        </div>

                        <!-- Stripe Card Element -->
                        <form @submit.prevent="handleSubmit">
                            <div class="mb-6">
                                <div id="card-element" class="p-4 border border-gray-300 rounded-lg"></div>
                            </div>

                            <!-- Security Notice -->
                            <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-blue-400 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-blue-900">Secure Payment</p>
                                        <p class="text-sm text-blue-700">Your payment information is encrypted and secure. We never store your card details.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button
                                type="submit"
                                :disabled="processing || !clientSecret"
                                :class="[
                                    'w-full py-3 px-4 rounded-lg font-medium text-white transition-colors',
                                    processing || !clientSecret
                                        ? 'bg-gray-400 cursor-not-allowed'
                                        : 'bg-green-600 hover:bg-green-700'
                                ]"
                            >
                                <span v-if="processing" class="flex items-center justify-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Processing...
                                </span>
                                <span v-else-if="!clientSecret">
                                    Loading...
                                </span>
                                <span v-else>
                                    Pay ${{ order.total }} and Confirm Booking
                                </span>
                            </button>

                            <!-- Powered by Stripe -->
                            <div class="mt-4 text-center">
                                <p class="text-xs text-gray-500">Powered by Stripe</p>
                            </div>
                        </form>
                    </div>

                    <!-- Terms -->
                    <div class="mt-6 text-center text-sm text-gray-600">
                        <p>By completing this booking, you agree to our terms of service and cancellation policy.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
