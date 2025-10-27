<script setup>
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
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
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

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Success State -->
            <div v-if="success" class="text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-green-100 mb-4">
                    <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Booking Cancelled</h1>
                <p class="text-gray-600 mb-8">Your appointment has been successfully cancelled</p>

                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <p class="text-gray-700 mb-4">A confirmation email has been sent to {{ booking.customer_email }}</p>
                    <p class="text-sm text-gray-600">If you paid for this booking, a refund will be processed within 5-10 business days.</p>
                </div>

                <Link
                    :href="route('companies.index')"
                    class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                >
                    Browse Companies
                </Link>
            </div>

            <!-- Cancellation Form -->
            <div v-else>
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Cancel Appointment</h1>
                    <p class="mt-2 text-gray-600">We're sorry to see you go</p>
                </div>

                <!-- Booking Details -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Appointment Details</h2>

                    <div class="space-y-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="font-semibold text-gray-900">{{ formatDate(booking.booking_date) }}</p>
                                <p class="text-gray-600">{{ booking.start_time.substring(0, 5) }} - {{ booking.end_time.substring(0, 5) }}</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="font-semibold text-gray-900">{{ booking.staff.first_name }} {{ booking.staff.last_name }}</p>
                                <p class="text-gray-600">{{ booking.company.name }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Warning Message -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
                    <div class="flex">
                        <svg class="w-6 h-6 text-yellow-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h3 class="font-semibold text-yellow-900 mb-2">Important</h3>
                            <ul class="text-sm text-yellow-800 space-y-1">
                                <li>• This action cannot be undone</li>
                                <li>• Cancellations must be made at least 24 hours in advance</li>
                                <li>• Refunds (if applicable) take 5-10 business days to process</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Cancellation Form -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <form @submit.prevent="cancelBooking">
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Reason for cancellation (Optional)
                            </label>
                            <textarea
                                v-model="cancellationReason"
                                rows="4"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Help us improve by letting us know why you're cancelling..."
                            ></textarea>
                        </div>

                        <!-- Error Message -->
                        <div v-if="error" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex">
                                <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-sm text-red-800">{{ error }}</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <Link
                                :href="route('companies.index')"
                                class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-center font-medium"
                            >
                                Keep Appointment
                            </Link>
                            <button
                                type="submit"
                                :disabled="processing"
                                :class="[
                                    'flex-1 px-4 py-3 rounded-lg font-medium text-white transition-colors',
                                    processing
                                        ? 'bg-gray-400 cursor-not-allowed'
                                        : 'bg-red-600 hover:bg-red-700'
                                ]"
                            >
                                <span v-if="processing" class="flex items-center justify-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Cancelling...
                                </span>
                                <span v-else>
                                    Cancel Appointment
                                </span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Contact Support -->
                <div class="mt-6 text-center text-sm text-gray-600">
                    <p>
                        Need help?
                        <a v-if="booking.company.email" :href="`mailto:${booking.company.email}`" class="text-blue-600 hover:underline">
                            Contact {{ booking.company.name }}
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
