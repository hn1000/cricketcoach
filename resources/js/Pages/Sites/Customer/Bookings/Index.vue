<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import Layout from '@/Layouts/Sites/Customer/Layout.vue';
import { ref, watch } from 'vue';

const props = defineProps({
    bookings: Object,
    filters: Object,
});

const filters = ref({
    status: props.filters.status || 'all',
    search: props.filters.search || '',
    date_from: props.filters.date_from || '',
    date_to: props.filters.date_to || '',
});

const applyFilters = () => {
    router.get(route('customer.bookings.index'), filters.value, {
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
        weekday: 'short',
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
        'confirmed': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        'pending_payment': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
        'completed': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
        'cancelled': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
};
</script>

<template>
    <Layout>
        <Head title="My Bookings" />

        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">My Bookings</h1>
            <p class="text-gray-600 dark:text-gray-400">View and manage all your bookings</p>
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
                        <option value="confirmed">Confirmed</option>
                        <option value="pending_payment">Pending Payment</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                    <input
                        v-model="filters.search"
                        @input="applyFilters"
                        type="text"
                        placeholder="Search by company or staff..."
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
                <button
                    @click="clearFilters"
                    class="text-sm text-blue-600 hover:underline dark:text-blue-400"
                >
                    Clear Filters
                </button>
            </div>
        </div>

        <!-- Bookings List -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div v-if="bookings.data.length === 0" class="p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="mt-2 text-gray-500 dark:text-gray-400">No bookings found</p>
                <Link :href="route('home')" class="mt-2 inline-block text-blue-600 hover:underline dark:text-blue-400">
                    Make your first booking
                </Link>
            </div>

            <div v-else class="divide-y divide-gray-200 dark:divide-gray-700">
                <div
                    v-for="booking in bookings.data"
                    :key="booking.id"
                    class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                >
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="flex-1 mb-3 md:mb-0">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ booking.company.name }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">with {{ booking.staff.name }}</p>
                                </div>
                                <span
                                    class="ml-2 px-2 py-1 text-xs font-medium rounded-full whitespace-nowrap"
                                    :class="getStatusColor(booking.status)"
                                >
                                    {{ booking.status.replace('_', ' ') }}
                                </span>
                            </div>
                            <div class="space-y-1 text-sm text-gray-600 dark:text-gray-400">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ formatDate(booking.booking_date) }}
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ booking.start_time }} - {{ booking.end_time }} ({{ booking.duration }} min)
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-right">
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ formatCurrency(booking.price) }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Booking #{{ booking.id }}</p>
                            </div>
                            <Link
                                :href="route('customer.bookings.show', booking.id)"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition"
                            >
                                View Details
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div v-if="bookings.data.length > 0" class="mt-6 flex justify-between items-center">
            <p class="text-sm text-gray-700 dark:text-gray-300">
                Showing {{ bookings.from }} to {{ bookings.to }} of {{ bookings.total }} results
            </p>
            <div class="flex space-x-2">
                <Link
                    v-for="link in bookings.links"
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
