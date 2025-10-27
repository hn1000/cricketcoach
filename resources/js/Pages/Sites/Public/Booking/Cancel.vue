<script setup>
import PublicLayout from '@/Layouts/Sites/Public/Layout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    booking: Object,
});

const cancellationReason = ref('');
const processing = ref(false);
const error = ref(null);
const success = ref(false);

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};

const cancelBooking = async () => {
    if (processing.value) return;

    processing.value = true;
    error.value = null;

    try {
        const response = await fetch(route('booking.cancel', props.booking.confirmation_token), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
             //   'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                cancellation_reason: cancellationReason.value,
            }),
        });

        const data = await response.json();

        if (response.ok) {
            success.value = true;
        } else {
            error.value = data.message || 'Failed to cancel booking';
        }
    } catch (err) {
        error.value = 'An unexpected error occurred. Please try again.';
    } finally {
        processing.value = false;
    }
};
</script>

<template>
    <Head title="Cancel Booking" />

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
                                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Cancel Booking</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
        </section>

        <section class="bg-gray-50 dark:bg-gray-900 py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Success State -->
            <div v-if="success" class="text-center">
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-green-100 dark:bg-green-900/30 mb-6 animate-bounce">
                    <svg class="w-14 h-14 text-green-600 dark:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white mb-3">Booking Cancelled</h1>
                <p class="text-lg text-gray-600 dark:text-gray-400 mb-8">Your cricket coaching session has been successfully cancelled</p>

                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6 md:p-8 mb-6 text-left">
                    <div class="flex items-start mb-4">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                        </svg>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white mb-1">Confirmation Email Sent</p>
                            <p class="text-gray-600 dark:text-gray-400">A cancellation confirmation has been sent to <span class="font-medium">{{ booking.customer_email }}</span></p>
                        </div>
                    </div>
                    <div class="flex items-start border-t border-gray-200 dark:border-gray-700 pt-4">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white mb-1">Refund Processing</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">If you paid for this booking, a refund will be processed within 5-10 business days to your original payment method.</p>
                        </div>
                    </div>
                </div>

                <Link
                    :href="route('companies.index')"
                    class="inline-flex items-center px-6 py-3 bg-blue-700 hover:bg-blue-800 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-medium rounded-lg transition-colors focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Browse Cricket Academies
                </Link>
            </div>

            <!-- Cancellation Form -->
            <div v-else>
                <div class="text-center mb-8">
                    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white">Cancel Cricket Coaching Session</h1>
                    <p class="mt-3 text-lg text-gray-600 dark:text-gray-400">We're sorry to see you go</p>
                </div>

                <!-- Booking Details -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6 md:p-8 mb-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Session Details</h2>

                    <div class="space-y-5">
                        <div class="flex items-start p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-blue-900 dark:text-blue-400 mb-1">Date & Time</p>
                                <p class="font-bold text-gray-900 dark:text-white">{{ formatDate(booking.booking_date) }}</p>
                                <p class="text-gray-700 dark:text-gray-300">{{ booking.start_time.substring(0, 5) }} - {{ booking.end_time.substring(0, 5) }}</p>
                            </div>
                        </div>

                        <div class="flex items-start p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-100 dark:border-green-800">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-green-900 dark:text-green-400 mb-1">Your Coach</p>
                                <p class="font-bold text-gray-900 dark:text-white">{{ booking.staff.first_name }} {{ booking.staff.last_name }}</p>
                                <p class="text-gray-700 dark:text-gray-300">{{ booking.company.name }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Warning Message -->
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-6 mb-6">
                    <div class="flex">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h3 class="font-semibold text-yellow-900 dark:text-yellow-400 mb-2">Important Cancellation Policy</h3>
                            <ul class="text-sm text-yellow-800 dark:text-yellow-300 space-y-1">
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    This action cannot be undone
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Cancellations must be made at least 24 hours in advance
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Refunds (if applicable) take 5-10 business days to process
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Cancellation Form -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6 md:p-8">
                    <form @submit.prevent="cancelBooking">
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                Reason for cancellation (Optional)
                            </label>
                            <textarea
                                v-model="cancellationReason"
                                rows="4"
                                class="w-full px-4 py-3 text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-4 focus:ring-blue-300 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Help us improve by letting us know why you're cancelling..."
                            ></textarea>
                        </div>

                        <!-- Error Message -->
                        <div v-if="error" class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                            <div class="flex">
                                <svg class="w-5 h-5 text-red-400 dark:text-red-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-red-800 dark:text-red-400">Cancellation Error</p>
                                    <p class="text-sm text-red-700 dark:text-red-300 mt-1">{{ error }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4">
                            <Link
                                :href="route('companies.index')"
                                class="flex-1 px-6 py-3 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors text-center font-medium focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700"
                            >
                                Keep My Session
                            </Link>
                            <button
                                type="submit"
                                :disabled="processing"
                                :class="[
                                    'flex-1 px-6 py-3 rounded-lg font-medium text-white transition-colors focus:ring-4',
                                    processing
                                        ? 'bg-gray-400 dark:bg-gray-600 cursor-not-allowed'
                                        : 'bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800 focus:ring-red-300 dark:focus:ring-red-800'
                                ]"
                            >
                                <span v-if="processing" class="flex items-center justify-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Cancelling Session...
                                </span>
                                <span v-else>
                                    Confirm Cancellation
                                </span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Contact Support -->
                <div class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
                    <p>
                        Need help?
                        <a v-if="booking.company.email" :href="`mailto:${booking.company.email}`" class="text-blue-600 dark:text-blue-500 hover:underline font-medium">
                            Contact {{ booking.company.name }}
                        </a>
                    </p>
                </div>
            </div>
        </div>
        </section>
    </PublicLayout>
</template>
