<script setup>
import PublicLayout from '@/Layouts/Sites/Public/Layout.vue';
import { Head, Link } from '@inertiajs/vue3';
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
const mockMode = ref(false);

onMounted(async () => {
    // Create payment intent
    await createPaymentIntent();

    // If not in mock mode, load Stripe
    if (!mockMode.value) {
        stripe.value = await loadStripe(props.stripeKey);

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
    }
});

const clientSecret = ref(null);

const createPaymentIntent = async () => {
    try {
        const response = await fetch(route('checkout.payment-intent', props.order.id), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Mock-Payment': 'true', // Enable mock mode
            },
        });

        const data = await response.json();

        if (response.ok) {
            clientSecret.value = data.clientSecret;
            paymentIntentId.value = data.paymentIntentId;
            mockMode.value = data.mock || false;
        } else {
            console.error('Payment intent creation failed:', response.status, data);
            error.value = data.error || 'Failed to initialize payment';
        }
    } catch (err) {
        console.error('Payment intent error:', err);
        error.value = 'Failed to connect to payment service: ' + err.message;
    }
};

const handleSubmit = async (mockSuccess = true) => {
    if (processing.value) {
        return;
    }

    // Skip Stripe validation in mock mode
    if (!mockMode.value && (!stripe.value || !elements.value)) {
        return;
    }

    processing.value = true;
    error.value = null;

    try {
        // If not in mock mode, confirm payment with Stripe
        if (!mockMode.value) {
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
        }

        // Payment successful (or mock), confirm with backend
        const response = await fetch(route('checkout.confirm', props.order.id), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                payment_intent_id: paymentIntentId.value,
                mock_success: mockSuccess,
            }),
        });

        const data = await response.json();

        if (data.success) {
            window.location.href = data.redirect_url;
        } else {
            error.value = data.error || data.message || 'Payment confirmation failed';
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
    <Head title="Secure Checkout" />

    <PublicLayout>
        <!-- Breadcrumb -->
        <section class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <Link :href="route('home')" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                                </svg>
                                Home
                            </Link>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Checkout</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
        </section>

        <section class="bg-gray-50 dark:bg-gray-900 py-12">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white">Complete Your Booking</h1>
                    <p class="mt-3 text-lg text-gray-600 dark:text-gray-400">Secure payment powered by Stripe</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Order Summary (Left Side) -->
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6 lg:sticky lg:top-6">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Order Summary</h2>

                            <div class="space-y-5">
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Academy</p>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ order.company.name }}</p>
                                </div>

                                <div v-if="booking">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Session Details</p>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ formatDate(booking.booking_date) }}</p>
                                    <p class="text-gray-700 dark:text-gray-300">{{ booking.start_time.substring(0, 5) }} - {{ booking.end_time.substring(0, 5) }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Duration: {{ booking.duration_minutes }} minutes</p>
                                </div>

                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Customer</p>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ order.customer_name }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ order.customer_email }}</p>
                                </div>

                                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                    <div class="flex justify-between mb-3">
                                        <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                                        <span class="text-gray-900 dark:text-white font-medium">${{ order.subtotal }}</span>
                                    </div>
                                    <div class="flex justify-between mb-3">
                                        <span class="text-gray-600 dark:text-gray-400">Tax</span>
                                        <span class="text-gray-900 dark:text-white font-medium">${{ order.tax }}</span>
                                    </div>
                                    <div class="flex justify-between text-xl font-bold border-t border-gray-200 dark:border-gray-700 pt-4 mt-3">
                                        <span class="text-gray-900 dark:text-white">Total</span>
                                        <span class="text-blue-600 dark:text-blue-500">${{ order.total }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Form (Right Side) -->
                    <div class="lg:col-span-2">
                        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6 md:p-8">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Payment Information</h2>

                            <!-- Error Message -->
                            <div v-if="error" class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-red-400 dark:text-red-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-red-800 dark:text-red-400">Payment Error</p>
                                        <p class="text-sm text-red-700 dark:text-red-300 mt-1">{{ error }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Mock Payment Mode -->
                            <div v-if="mockMode">
                                <!-- Mock Mode Notice -->
                                <div class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                                    <div class="flex">
                                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-semibold text-yellow-900 dark:text-yellow-400">Development Mode</p>
                                            <p class="text-sm text-yellow-800 dark:text-yellow-300 mt-1">Payment simulation enabled. Click a button below to simulate a successful or failed payment.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Mock Payment Buttons -->
                                <div class="space-y-4">
                                    <button
                                        @click="handleSubmit(true)"
                                        :disabled="processing"
                                        :class="[
                                            'w-full py-4 px-6 rounded-lg font-semibold text-white text-lg transition-colors focus:ring-4 focus:ring-green-300 dark:focus:ring-green-800',
                                            processing
                                                ? 'bg-gray-400 dark:bg-gray-600 cursor-not-allowed'
                                                : 'bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800'
                                        ]"
                                    >
                                        <span v-if="processing" class="flex items-center justify-center">
                                            <svg class="animate-spin -ml-1 mr-3 h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Processing Payment...
                                        </span>
                                        <span v-else class="flex items-center justify-center">
                                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Simulate Successful Payment (${{ order.total }})
                                        </span>
                                    </button>

                                    <button
                                        @click="handleSubmit(false)"
                                        :disabled="processing"
                                        :class="[
                                            'w-full py-4 px-6 rounded-lg font-semibold text-white text-lg transition-colors focus:ring-4 focus:ring-red-300 dark:focus:ring-red-800',
                                            processing
                                                ? 'bg-gray-400 dark:bg-gray-600 cursor-not-allowed'
                                                : 'bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800'
                                        ]"
                                    >
                                        <span v-if="processing" class="flex items-center justify-center">
                                            <svg class="animate-spin -ml-1 mr-3 h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Processing Payment...
                                        </span>
                                        <span v-else class="flex items-center justify-center">
                                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Simulate Failed Payment
                                        </span>
                                    </button>
                                </div>
                            </div>

                            <!-- Real Stripe Card Element -->
                            <form v-else @submit.prevent="handleSubmit">
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-3">
                                        Card Details
                                    </label>
                                    <div id="card-element" class="p-4 border-2 border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700"></div>
                                </div>

                                <!-- Security Notice -->
                                <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-lg">
                                    <div class="flex">
                                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-semibold text-blue-900 dark:text-blue-400">Secure Payment</p>
                                            <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">Your payment information is encrypted and secure. We never store your card details. All payments are processed by Stripe.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <button
                                    type="submit"
                                    :disabled="processing || !clientSecret"
                                    :class="[
                                        'w-full py-4 px-6 rounded-lg font-semibold text-white text-lg transition-colors focus:ring-4 focus:ring-green-300 dark:focus:ring-green-800',
                                        processing || !clientSecret
                                            ? 'bg-gray-400 dark:bg-gray-600 cursor-not-allowed'
                                            : 'bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800'
                                    ]"
                                >
                                    <span v-if="processing" class="flex items-center justify-center">
                                        <svg class="animate-spin -ml-1 mr-3 h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Processing Payment...
                                    </span>
                                    <span v-else-if="!clientSecret">
                                        <svg class="animate-spin inline-block mr-3 h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Loading...
                                    </span>
                                    <span v-else class="flex items-center justify-center">
                                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Pay ${{ order.total }} and Confirm Booking
                                    </span>
                                </button>

                                <!-- Powered by Stripe -->
                                <div class="mt-4 flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Secured by Stripe</p>
                                </div>
                            </form>
                        </div>

                        <!-- Terms -->
                        <div class="mt-6 p-4 bg-gray-100 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <p class="text-sm text-gray-600 dark:text-gray-400 text-center">
                                By completing this booking, you agree to our <a href="#" class="text-blue-600 dark:text-blue-500 hover:underline">terms of service</a> and <a href="#" class="text-blue-600 dark:text-blue-500 hover:underline">cancellation policy</a>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
