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

const addToCalendar = () => {
    const start = new Date(`${props.booking.booking_date}T${props.booking.start_time}`);
    const end = new Date(`${props.booking.booking_date}T${props.booking.end_time}`);

    const title = `Appointment with ${props.booking.staff.first_name} ${props.booking.staff.last_name}`;
    const location = props.booking.company.address || props.booking.company.name;

    // Google Calendar URL
    const googleUrl = `https://www.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(title)}&dates=${start.toISOString().replace(/[-:]/g, '').split('.')[0]}Z/${end.toISOString().replace(/[-:]/g, '').split('.')[0]}Z&location=${encodeURIComponent(location)}`;

    window.open(googleUrl, '_blank');
};
</script>

<template>
    <Head title="Booking Confirmed" />

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Success Icon -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-green-100 mb-4">
                    <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Booking Confirmed!</h1>
                <p class="mt-2 text-gray-600">Your appointment has been successfully booked</p>
            </div>

            <!-- Booking Details Card -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-4">
                    <h2 class="text-xl font-semibold text-white">Appointment Details</h2>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Date & Time -->
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-gray-400 mr-4 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm text-gray-600">When</p>
                            <p class="text-lg font-semibold text-gray-900">{{ formatDate(booking.booking_date) }}</p>
                            <p class="text-gray-700">{{ booking.start_time.substring(0, 5) }} - {{ booking.end_time.substring(0, 5) }}</p>
                            <p class="text-sm text-gray-600">Duration: {{ booking.duration }} minutes</p>
                        </div>
                    </div>

                    <!-- Staff Member -->
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-gray-400 mr-4 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm text-gray-600">With</p>
                            <p class="text-lg font-semibold text-gray-900">{{ booking.staff.first_name }} {{ booking.staff.last_name }}</p>
                            <p v-if="booking.staff.position" class="text-gray-700">{{ booking.staff.position }}</p>
                        </div>
                    </div>

                    <!-- Company -->
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-gray-400 mr-4 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm text-gray-600">At</p>
                            <p class="text-lg font-semibold text-gray-900">{{ booking.company.name }}</p>
                            <p v-if="booking.company.address" class="text-gray-700">{{ booking.company.address }}</p>
                        </div>
                    </div>

                    <!-- Customer Info -->
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-gray-400 mr-4 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                        </svg>
                        <div>
                            <p class="text-sm text-gray-600">Confirmation sent to</p>
                            <p class="text-lg font-semibold text-gray-900">{{ booking.customer_name }}</p>
                            <p class="text-gray-700">{{ booking.customer_email }}</p>
                        </div>
                    </div>

                    <!-- Order Info -->
                    <div v-if="booking.order" class="border-t border-gray-200 pt-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-600">Order Number</p>
                                <p class="text-lg font-semibold text-gray-900">{{ booking.order.order_number }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600">Amount Paid</p>
                                <p class="text-lg font-semibold text-green-600">${{ booking.order.total }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">What's Next?</h3>

                <div class="space-y-3">
                    <button
                        @click="addToCalendar"
                        class="w-full flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Add to Calendar
                    </button>

                    <Link
                        :href="route('companies.index')"
                        class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
                    >
                        Book Another Appointment
                    </Link>
                </div>
            </div>

            <!-- Important Notice -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <div class="flex">
                    <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h4 class="font-semibold text-blue-900 mb-2">Important Information</h4>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li>• A confirmation email has been sent to {{ booking.customer_email }}</li>
                            <li>• You will receive a reminder 24 hours before your appointment</li>
                            <li>• Please arrive 5 minutes early for your appointment</li>
                            <li>• Cancellations must be made at least 24 hours in advance</li>
                        </ul>

                        <div class="mt-4">
                            <Link
                                :href="route('booking.cancel.form', booking.confirmation_token)"
                                class="text-sm text-blue-600 hover:text-blue-800 underline"
                            >
                                Need to cancel or reschedule?
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Support -->
            <div class="mt-6 text-center text-sm text-gray-600">
                <p>
                    Questions about your booking?
                    <a v-if="booking.company.email" :href="`mailto:${booking.company.email}`" class="text-blue-600 hover:underline">
                        Contact {{ booking.company.name }}
                    </a>
                </p>
            </div>
        </div>
    </div>
</template>
