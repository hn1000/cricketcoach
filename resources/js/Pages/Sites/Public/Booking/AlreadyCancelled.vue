<script setup>
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    booking: Object,
});

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
    <Head title="Booking Already Cancelled" />

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 mb-4">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Already Cancelled</h1>
                <p class="text-gray-600 mb-8">This appointment has already been cancelled</p>

                <!-- Booking Details -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6 text-left">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Appointment Details</h2>

                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Date:</span>
                            <span class="font-medium text-gray-900">{{ formatDate(booking.booking_date) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Time:</span>
                            <span class="font-medium text-gray-900">{{ booking.start_time.substring(0, 5) }} - {{ booking.end_time.substring(0, 5) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Staff:</span>
                            <span class="font-medium text-gray-900">{{ booking.staff.first_name }} {{ booking.staff.last_name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Company:</span>
                            <span class="font-medium text-gray-900">{{ booking.company.name }}</span>
                        </div>
                        <div class="flex justify-between border-t border-gray-200 pt-3">
                            <span class="text-gray-600">Status:</span>
                            <span class="font-medium text-red-600">Cancelled</span>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6 text-left">
                    <div class="flex">
                        <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h4 class="font-semibold text-blue-900 mb-2">Need to book again?</h4>
                            <p class="text-sm text-blue-800">You can make a new appointment anytime by browsing our available service providers.</p>
                        </div>
                    </div>
                </div>

                <Link
                    :href="route('companies.index')"
                    class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Book New Appointment
                </Link>

                <!-- Contact Support -->
                <div class="mt-6 text-sm text-gray-600">
                    <p>
                        Questions?
                        <a v-if="booking.company.email" :href="`mailto:${booking.company.email}`" class="text-blue-600 hover:underline">
                            Contact {{ booking.company.name }}
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
