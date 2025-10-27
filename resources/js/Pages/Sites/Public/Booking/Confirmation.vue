<script setup>
import PublicLayout from '@/Layouts/Sites/Public/Layout.vue';
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

    <PublicLayout>
        <section class="bg-gray-50 dark:bg-gray-900 py-12">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Success Icon -->
                <div class="text-center mb-10">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-green-100 dark:bg-green-900/30 mb-6 animate-bounce">
                        <svg class="w-14 h-14 text-green-600 dark:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-3">Booking Confirmed!</h1>
                    <p class="text-lg text-gray-600 dark:text-gray-400">Your coaching session has been successfully booked</p>
                </div>

                <!-- Booking Details Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-lg overflow-hidden mb-6">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 dark:from-blue-700 dark:to-blue-800 px-6 py-5">
                        <h2 class="text-2xl font-bold text-white">Session Details</h2>
                    </div>

                    <div class="p-6 md:p-8 space-y-6">
                        <!-- Date & Time -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">When</p>
                                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ formatDate(booking.booking_date) }}</p>
                                <p class="text-gray-700 dark:text-gray-300 mt-1">{{ booking.start_time.substring(0, 5) }} - {{ booking.end_time.substring(0, 5) }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Duration: {{ booking.duration }} minutes</p>
                            </div>
                        </div>

                        <!-- Staff Member -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Your Coach</p>
                                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ booking.staff.first_name }} {{ booking.staff.last_name }}</p>
                                <p v-if="booking.staff.position" class="text-gray-700 dark:text-gray-300 mt-1">{{ booking.staff.position }}</p>
                            </div>
                        </div>

                        <!-- Company -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Academy</p>
                                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ booking.company.name }}</p>
                                <p v-if="booking.company.address" class="text-gray-700 dark:text-gray-300 mt-1">{{ booking.company.address }}</p>
                            </div>
                        </div>

                        <!-- Customer Info -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Confirmation sent to</p>
                                <p class="text-xl font-bold text-gray-900 dark:text-white">{{ booking.customer_name }}</p>
                                <p class="text-gray-700 dark:text-gray-300 mt-1">{{ booking.customer_email }}</p>
                            </div>
                        </div>

                        <!-- Order Info -->
                        <div v-if="booking.order" class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Order Number</p>
                                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ booking.order.order_number }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Amount Paid</p>
                                    <p class="text-2xl font-bold text-green-600 dark:text-green-500">${{ booking.order.total }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6 mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-5">What's Next?</h3>

                    <div class="space-y-3">
                        <button
                            @click="addToCalendar"
                            class="w-full flex items-center justify-center px-6 py-4 bg-blue-700 text-white rounded-lg hover:bg-blue-800 transition-colors font-medium focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        >
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Add to Calendar
                        </button>

                        <Link
                            :href="route('companies.index')"
                            class="w-full flex items-center justify-center px-6 py-4 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors font-medium focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Book Another Session
                        </Link>
                    </div>
                </div>

                <!-- Important Notice -->
                <div class="bg-blue-50 dark:bg-blue-900/20 border-2 border-blue-200 dark:border-blue-800 rounded-lg p-6 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-bold text-blue-900 dark:text-blue-400 mb-3 text-lg">Important Information</h4>
                            <ul class="text-sm text-blue-800 dark:text-blue-300 space-y-2">
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>A confirmation email has been sent to <strong>{{ booking.customer_email }}</strong></span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>You will receive a reminder 24 hours before your session</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Please arrive 5-10 minutes early with appropriate sports attire</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Cancellations must be made at least 24 hours in advance</span>
                                </li>
                            </ul>

                            <div class="mt-5 pt-4 border-t border-blue-200 dark:border-blue-800">
                                <Link
                                    :href="route('booking.cancel.form', booking.confirmation_token)"
                                    class="inline-flex items-center text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 hover:underline"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Need to cancel or reschedule?
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Support -->
                <div class="text-center p-4 bg-gray-100 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-lg">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Questions about your booking?
                        <a v-if="booking.company.email" :href="`mailto:${booking.company.email}`" class="text-blue-600 dark:text-blue-500 hover:underline font-medium">
                            Contact {{ booking.company.name }}
                        </a>
                    </p>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
