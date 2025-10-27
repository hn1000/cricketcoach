<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import Layout from '@/Layouts/Sites/Customer/Layout.vue';
import { ref } from 'vue';

const props = defineProps({
    orders: Object,
    filters: Object,
});

const filters = ref({
    status: props.filters.status || 'all',
    search: props.filters.search || '',
    date_from: props.filters.date_from || '',
    date_to: props.filters.date_to || '',
});

const applyFilters = () => {
    router.get(route('customer.orders.index'), filters.value, {
        preserveState: true,
        replace: true,
    });
};

const clearFilters = () => {
    filters.value = { status: 'all', search: '', date_from: '', date_to: '' };
    applyFilters();
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
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
        <Head title="My Orders" />

        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">My Orders</h1>
            <p class="text-gray-600 dark:text-gray-400">View your order history and receipts</p>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                    <select
                        v-model="filters.status"
                        @change="applyFilters"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    >
                        <option value="all">All Statuses</option>
                        <option value="paid">Paid</option>
                        <option value="pending">Pending</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="refunded">Refunded</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search Order</label>
                    <input
                        v-model="filters.search"
                        @input="applyFilters"
                        type="text"
                        placeholder="Order number..."
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">From Date</label>
                    <input
                        v-model="filters.date_from"
                        @change="applyFilters"
                        type="date"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">To Date</label>
                    <input
                        v-model="filters.date_to"
                        @change="applyFilters"
                        type="date"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    />
                </div>
            </div>
            <div class="mt-4">
                <button @click="clearFilters" class="text-sm text-blue-600 hover:underline dark:text-blue-400">
                    Clear Filters
                </button>
            </div>
        </div>

        <!-- Orders List -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div v-if="orders.data.length === 0" class="p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <p class="mt-2 text-gray-500 dark:text-gray-400">No orders found</p>
            </div>

            <div v-else class="divide-y divide-gray-200 dark:divide-gray-700">
                <div
                    v-for="order in orders.data"
                    :key="order.id"
                    class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                >
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="flex-1 mb-3 md:mb-0">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Order #{{ order.order_number }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ order.items.length }} item(s)</p>
                                </div>
                                <span
                                    class="ml-2 px-2 py-1 text-xs font-medium rounded-full whitespace-nowrap"
                                    :class="getStatusColor(order.status)"
                                >
                                    {{ order.status.toUpperCase() }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                <p>{{ formatDate(order.created_at) }}</p>
                                <p v-if="order.payment">Payment Method: <span class="capitalize">{{ order.payment.payment_gateway }}</span></p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-right">
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ formatCurrency(order.total) }}</p>
                            </div>
                            <Link
                                :href="route('customer.orders.show', order.id)"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition"
                            >
                                View Order
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div v-if="orders.data.length > 0" class="mt-6 flex justify-between items-center">
            <p class="text-sm text-gray-700 dark:text-gray-300">
                Showing {{ orders.from }} to {{ orders.to }} of {{ orders.total }} results
            </p>
            <div class="flex space-x-2">
                <Link
                    v-for="link in orders.links"
                    :key="link.label"
                    :href="link.url"
                    v-html="link.label"
                    class="px-3 py-2 text-sm rounded-lg"
                    :class="link.active
                        ? 'bg-blue-600 text-white'
                        : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'"
                />
            </div>
        </div>
    </Layout>
</template>
