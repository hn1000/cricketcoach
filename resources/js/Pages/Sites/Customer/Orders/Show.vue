<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Layout from '@/Layouts/Sites/Customer/Layout.vue';

defineProps({
    order: Object,
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(amount);
};

const getStatusColor = (status) => {
    const colors = {
        'paid': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        'pending': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
        'cancelled': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
        'refunded': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
};
</script>

<template>
    <Layout>
        <Head :title="`Order #${order.order_number}`" />

        <div class="mb-4">
            <Link :href="route('customer.orders.index')" class="inline-flex items-center text-blue-600 hover:underline dark:text-blue-400">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                </svg>
                Back to Orders
            </Link>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Summary -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-start">
                            <div>
                                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-1">Order #{{ order.order_number }}</h1>
                                <p class="text-gray-600 dark:text-gray-400">Placed on {{ formatDate(order.created_at) }}</p>
                            </div>
                            <span class="px-3 py-1 text-sm font-medium rounded-full" :class="getStatusColor(order.status)">
                                {{ order.status.toUpperCase() }}
                            </span>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Order Items</h2>
                        <div class="space-y-4">
                            <div v-for="item in order.items" :key="item.id" class="flex justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-900 dark:text-white">Booking Session</h3>
                                    <p v-if="item.metadata" class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ item.metadata.staff_name }} - {{ item.metadata.booking_date }}
                                        at {{ item.metadata.start_time }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Quantity: {{ item.quantity }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ formatCurrency(item.total_price) }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ formatCurrency(item.unit_price) }} each</p>
                                </div>
                            </div>
                        </div>

                        <!-- Order Totals -->
                        <div class="mt-6 space-y-2">
                            <div class="flex justify-between text-gray-600 dark:text-gray-400">
                                <span>Subtotal</span>
                                <span>{{ formatCurrency(order.subtotal) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600 dark:text-gray-400">
                                <span>Tax</span>
                                <span>{{ formatCurrency(order.tax) }}</span>
                            </div>
                            <div class="flex justify-between text-lg font-semibold text-gray-900 dark:text-white pt-2 border-t border-gray-200 dark:border-gray-700">
                                <span>Total</span>
                                <span>{{ formatCurrency(order.total) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div v-if="order.payment" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Payment Information</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Payment Method</span>
                            <span class="text-gray-900 dark:text-white capitalize">{{ order.payment.payment_gateway }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Transaction ID</span>
                            <span class="text-gray-900 dark:text-white font-mono text-sm">{{ order.payment.gateway_transaction_id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Payment Status</span>
                            <span class="px-2 py-1 text-xs font-medium rounded-full" :class="getStatusColor(order.payment.status)">
                                {{ order.payment.status }}
                            </span>
                        </div>
                        <div v-if="order.payment.processed_at" class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Processed At</span>
                            <span class="text-gray-900 dark:text-white">{{ formatDate(order.payment.processed_at) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Customer Information -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Customer Information</h2>
                    <div class="space-y-2 text-sm">
                        <p class="text-gray-900 dark:text-white font-medium">{{ order.customer_name }}</p>
                        <p class="text-gray-600 dark:text-gray-400">{{ order.customer_email }}</p>
                        <p v-if="order.customer_phone" class="text-gray-600 dark:text-gray-400">{{ order.customer_phone }}</p>
                    </div>
                </div>

                <!-- Related Bookings -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Related Bookings</h2>
                    <div class="space-y-2">
                        <Link
                            v-for="item in order.items"
                            :key="item.id"
                            :href="route('customer.bookings.show', item.orderable_id)"
                            class="block p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition"
                        >
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Booking #{{ item.orderable_id }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">View booking details →</p>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
