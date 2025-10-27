<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Layout from '@/Layouts/Sites/Customer/Layout.vue';
import { computed } from 'vue';

const props = defineProps({
    upcomingBookings: Array,
    recentOrders: Array,
    stats: Object,
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        weekday: 'short',
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
};

const getStatusColor = (status) => {
    const colors = {
        'confirmed': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        'pending_payment': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
        'completed': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
        'cancelled': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
        'paid': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        'pending': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
    };
    return colors[status] || 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
};
</script>

<template>
    <Layout>
        <Head title="My Dashboard" />

        <div class="mb-4">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Dashboard</h1>
            <p class="text-gray-600 dark:text-gray-400">Welcome back! Here's an overview of your bookings and orders.</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow dark:bg-gray-800 p-4">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Bookings</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.total_bookings }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow dark:bg-gray-800 p-4">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Bookings</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.active_bookings }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow dark:bg-gray-800 p-4">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Spent</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ formatCurrency(stats.total_spent) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow dark:bg-gray-800 p-4">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-orange-100 dark:bg-orange-900">
                        <svg class="w-6 h-6 text-orange-600 dark:text-orange-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Notifications</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.unread_notifications }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Upcoming Bookings -->
            <div class="bg-white rounded-lg shadow dark:bg-gray-800">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Upcoming Bookings</h2>
                    <Link :href="route('customer.bookings.index')" class="text-sm text-blue-600 hover:underline dark:text-blue-400">
                        View All
                    </Link>
                </div>
                <div class="p-4">
                    <div v-if="upcomingBookings.length === 0" class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No upcoming bookings</p>
                        <Link :href="route('home')" class="mt-2 inline-block text-sm text-blue-600 hover:underline dark:text-blue-400">
                            Make a booking
                        </Link>
                    </div>
                    <div v-else class="space-y-3">
                        <div
                            v-for="booking in upcomingBookings"
                            :key="booking.id"
                            class="border border-gray-200 dark:border-gray-700 rounded-lg p-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                        >
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h3 class="font-medium text-gray-900 dark:text-white">{{ booking.company.name }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">with {{ booking.staff.name }}</p>
                                </div>
                                <span
                                    class="px-2 py-1 text-xs font-medium rounded-full"
                                    :class="getStatusColor(booking.status)"
                                >
                                    {{ booking.status.replace('_', ' ') }}
                                </span>
                            </div>
                            <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-2">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                                {{ formatDate(booking.booking_date) }} at {{ booking.start_time }}
                            </div>
                            <Link
                                :href="route('customer.bookings.show', booking.id)"
                                class="text-sm text-blue-600 hover:underline dark:text-blue-400"
                            >
                                View Details →
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="bg-white rounded-lg shadow dark:bg-gray-800">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Orders</h2>
                    <Link :href="route('customer.orders.index')" class="text-sm text-blue-600 hover:underline dark:text-blue-400">
                        View All
                    </Link>
                </div>
                <div class="p-4">
                    <div v-if="recentOrders.length === 0" class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No recent orders</p>
                    </div>
                    <div v-else class="space-y-3">
                        <div
                            v-for="order in recentOrders"
                            :key="order.id"
                            class="border border-gray-200 dark:border-gray-700 rounded-lg p-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                        >
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h3 class="font-medium text-gray-900 dark:text-white">Order #{{ order.order_number }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ order.items.length }} item(s)</p>
                                </div>
                                <span
                                    class="px-2 py-1 text-xs font-medium rounded-full"
                                    :class="getStatusColor(order.status)"
                                >
                                    {{ order.status }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ formatDate(order.created_at) }}</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ formatCurrency(order.total) }}</span>
                            </div>
                            <Link
                                :href="route('customer.orders.show', order.id)"
                                class="text-sm text-blue-600 hover:underline dark:text-blue-400 mt-2 inline-block"
                            >
                                View Order →
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-6 bg-white rounded-lg shadow dark:bg-gray-800 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <Link
                    :href="route('home')"
                    class="flex items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition"
                >
                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">New Booking</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Book a session</p>
                    </div>
                </Link>

                <Link
                    :href="route('customer.bookings.index')"
                    class="flex items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition"
                >
                    <svg class="w-8 h-8 text-green-600 dark:text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">View Bookings</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Manage all bookings</p>
                    </div>
                </Link>

                <Link
                    :href="route('customer.profile.edit')"
                    class="flex items-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/30 transition"
                >
                    <svg class="w-8 h-8 text-purple-600 dark:text-purple-400 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">Edit Profile</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Update your details</p>
                    </div>
                </Link>
            </div>
        </div>
    </Layout>
</template>
