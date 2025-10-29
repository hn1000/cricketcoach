<script setup>
import { Head, Link } from '@inertiajs/vue3';
import CompanyLayout from '@/Layouts/Sites/Company/Layout.vue';

const props = defineProps({
    company: Object,
    stats: Object,
    recentBookings: Array,
    recentEnquiries: Array,
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};

const getStatusClass = (status) => {
    const classes = {
        new: 'bg-blue-100 text-blue-800',
        read: 'bg-gray-100 text-gray-800',
        replied: 'bg-green-100 text-green-800',
        pending_payment: 'bg-yellow-100 text-yellow-800',
        confirmed: 'bg-blue-100 text-blue-800',
        completed: 'bg-green-100 text-green-800',
        cancelled: 'bg-red-100 text-red-800',
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
};
</script>

<template>
    <Head title="Company Dashboard" />

    <CompanyLayout>
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Welcome to {{ company.name }}</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                        <svg class="w-8 h-8 text-green-600 dark:text-green-300" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Coaches</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.totalCoaches }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Upcoming Bookings</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.upcomingBookings }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                        <svg class="w-8 h-8 text-purple-600 dark:text-purple-300" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">New Enquiries</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.newEnquiries }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
                        <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Bookings</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.totalBookings }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Bookings -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Bookings</h2>
                    <Link :href="route('company.bookings.index')" class="text-sm text-green-600 hover:text-green-700 dark:text-green-400">
                        View all →
                    </Link>
                </div>
                <div class="p-4">
                    <div v-if="recentBookings.length === 0" class="text-center py-8 text-gray-500 dark:text-gray-400">
                        No recent bookings
                    </div>
                    <div v-else class="space-y-3">
                        <div v-for="booking in recentBookings" :key="booking.id" class="border-b border-gray-200 dark:border-gray-700 pb-3 last:border-b-0">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ booking.user.name }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">with {{ booking.staff.first_name }} {{ booking.staff.last_name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ formatDate(booking.booking_date) }}</p>
                                </div>
                                <span :class="getStatusClass(booking.status)" class="px-2 py-1 text-xs font-medium rounded-full">
                                    {{ booking.status.replace('_', ' ') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Enquiries -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Enquiries</h2>
                    <Link :href="route('company.enquiries.index')" class="text-sm text-green-600 hover:text-green-700 dark:text-green-400">
                        View all →
                    </Link>
                </div>
                <div class="p-4">
                    <div v-if="recentEnquiries.length === 0" class="text-center py-8 text-gray-500 dark:text-gray-400">
                        No recent enquiries
                    </div>
                    <div v-else class="space-y-3">
                        <div v-for="enquiry in recentEnquiries" :key="enquiry.id" class="border-b border-gray-200 dark:border-gray-700 pb-3 last:border-b-0">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ enquiry.user.name }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">to {{ enquiry.staff.first_name }} {{ enquiry.staff.last_name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">{{ formatDate(enquiry.created_at) }}</p>
                                </div>
                                <span :class="getStatusClass(enquiry.status)" class="px-2 py-1 text-xs font-medium rounded-full">
                                    {{ enquiry.status }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </CompanyLayout>
</template>
