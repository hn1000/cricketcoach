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
</script>

<template>
    <Head title="Booking Already Cancelled" />

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
                                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Booking Status</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
        </section>

        <section class="bg-gray-50 dark:bg-gray-900 py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gray-100 dark:bg-gray-800 mb-6">
                    <svg class="w-14 h-14 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white mb-3">Already Cancelled</h1>
                <p class="text-lg text-gray-600 dark:text-gray-400 mb-8">This cricket coaching session has already been cancelled</p>

                <!-- Booking Details -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6 md:p-8 mb-6 text-left">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Session Details</h2>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Date:</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ formatDate(booking.booking_date) }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Time:</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ booking.start_time.substring(0, 5) }} - {{ booking.end_time.substring(0, 5) }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Coach:</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ booking.staff.first_name }} {{ booking.staff.last_name }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Academy:</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ booking.company.name }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-red-50 dark:bg-red-900/20 rounded-lg border-2 border-red-200 dark:border-red-800">
                            <span class="text-sm font-medium text-red-600 dark:text-red-400">Status:</span>
                            <span class="font-bold text-red-600 dark:text-red-400 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                Cancelled
                            </span>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6 mb-8 text-left">
                    <div class="flex">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h4 class="font-semibold text-blue-900 dark:text-blue-400 mb-2">Ready to book another session?</h4>
                            <p class="text-sm text-blue-800 dark:text-blue-300">You can make a new coaching appointment anytime by browsing our cricket academies and expert coaches.</p>
                        </div>
                    </div>
                </div>

                <Link
                    :href="route('companies.index')"
                    class="inline-flex items-center px-6 py-3 bg-blue-700 hover:bg-blue-800 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-medium rounded-lg transition-colors focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Book New Cricket Session
                </Link>

                <!-- Contact Support -->
                <div class="mt-6 text-sm text-gray-600 dark:text-gray-400">
                    <p>
                        Questions?
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
