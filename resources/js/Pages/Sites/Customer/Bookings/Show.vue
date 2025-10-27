<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import Layout from '@/Layouts/Sites/Customer/Layout.vue';
import { ref } from 'vue';

const props = defineProps({
    booking: Object,
});

const cancelModalOpen = ref(false);
const cancellationReason = ref('');
const cancelLoading = ref(false);
const cancelError = ref('');

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
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

const canCancel = () => {
    if (props.booking.status === 'cancelled' || props.booking.status === 'completed') {
        return false;
    }
    // Check if booking is at least 24 hours away
    const bookingDateTime = new Date(props.booking.booking_date + ' ' + props.booking.start_time);
    const hoursUntil = (bookingDateTime - new Date()) / (1000 * 60 * 60);
    return hoursUntil >= 24;
};

const cancelBooking = async () => {
    cancelLoading.value = true;
    cancelError.value = '';

    try {
        await router.post(
            route('customer.bookings.cancel', props.booking.id),
            { cancellation_reason: cancellationReason.value },
            {
                onSuccess: () => {
                    cancelModalOpen.value = false;
                    router.reload();
                },
                onError: (errors) => {
                    cancelError.value = errors.message || 'Failed to cancel booking';
                },
            }
        );
    } finally {
        cancelLoading.value = false;
    }
};
</script>

<template>
    <Layout>
        <Head :title="`Booking #${booking.id}`" />

        <!-- Back Button -->
        <div class="mb-4">
            <Link
                :href="route('customer.bookings.index')"
                class="inline-flex items-center text-blue-600 hover:underline dark:text-blue-400"
            >
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                </svg>
                Back to Bookings
            </Link>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Booking Details Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-start">
                            <div>
                                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-1">
                                    Booking #{{ booking.id }}
                                </h1>
                                <p class="text-gray-600 dark:text-gray-400">{{ booking.company.name }}</p>
                            </div>
                            <span
                                class="px-3 py-1 text-sm font-medium rounded-full"
                                :class="getStatusColor(booking.status)"
                            >
                                {{ booking.status.replace('_', ' ').toUpperCase() }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6 space-y-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Date & Time</h3>
                            <div class="flex items-center text-gray-900 dark:text-white">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                                {{ formatDate(booking.booking_date) }}
                            </div>
                            <div class="flex items-center text-gray-900 dark:text-white mt-2">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                {{ booking.start_time }} - {{ booking.end_time }} ({{ booking.duration }} minutes)
                            </div>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Staff Member</h3>
                            <p class="text-gray-900 dark:text-white">{{ booking.staff.name }}</p>
                        </div>

                        <div v-if="booking.notes">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Notes</h3>
                            <p class="text-gray-900 dark:text-white">{{ booking.notes }}</p>
                        </div>

                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex justify-between text-lg font-semibold">
                                <span class="text-gray-900 dark:text-white">Total Price</span>
                                <span class="text-gray-900 dark:text-white">{{ formatCurrency(booking.price) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div v-if="booking.order" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Payment Information</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Order Number</span>
                            <Link
                                :href="route('customer.orders.show', booking.order.id)"
                                class="text-blue-600 hover:underline dark:text-blue-400"
                            >
                                #{{ booking.order.order_number }}
                            </Link>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Payment Status</span>
                            <span
                                class="px-2 py-1 text-xs font-medium rounded-full"
                                :class="getStatusColor(booking.order.status)"
                            >
                                {{ booking.order.status }}
                            </span>
                        </div>
                        <div v-if="booking.order.payment" class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Payment Method</span>
                            <span class="text-gray-900 dark:text-white capitalize">{{ booking.order.payment.payment_gateway }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Company Info -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Company Details</h2>
                    <div class="space-y-3">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Company Name</h3>
                            <p class="text-gray-900 dark:text-white">{{ booking.company.name }}</p>
                        </div>
                        <div v-if="booking.company.email">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</h3>
                            <a :href="`mailto:${booking.company.email}`" class="text-blue-600 hover:underline dark:text-blue-400">
                                {{ booking.company.email }}
                            </a>
                        </div>
                        <div v-if="booking.company.phone">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</h3>
                            <a :href="`tel:${booking.company.phone}`" class="text-blue-600 hover:underline dark:text-blue-400">
                                {{ booking.company.phone }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Actions</h2>
                    <div class="space-y-3">
                        <button
                            v-if="canCancel()"
                            @click="cancelModalOpen = true"
                            class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition"
                        >
                            Cancel Booking
                        </button>
                        <p v-else-if="booking.status !== 'cancelled'" class="text-sm text-gray-500 dark:text-gray-400">
                            Bookings must be cancelled at least 24 hours in advance
                        </p>
                    </div>
                </div>

                <!-- Confirmation Token -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Confirmation</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Confirmation Token</p>
                    <p class="text-xs font-mono bg-gray-100 dark:bg-gray-700 p-2 rounded break-all">
                        {{ booking.confirmation_token }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Cancel Modal -->
        <div v-if="cancelModalOpen" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="cancelModalOpen = false"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-lg max-w-lg w-full p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Cancel Booking</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Are you sure you want to cancel this booking? This action cannot be undone.
                    </p>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Cancellation Reason (Optional)
                        </label>
                        <textarea
                            v-model="cancellationReason"
                            rows="3"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            placeholder="Let us know why you're cancelling..."
                        ></textarea>
                    </div>
                    <div v-if="cancelError" class="mb-4 p-3 bg-red-100 dark:bg-red-900/20 text-red-700 dark:text-red-400 rounded-lg">
                        {{ cancelError }}
                    </div>
                    <div class="flex space-x-3">
                        <button
                            @click="cancelModalOpen = false"
                            class="flex-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-900 dark:text-white rounded-lg transition"
                        >
                            Keep Booking
                        </button>
                        <button
                            @click="cancelBooking"
                            :disabled="cancelLoading"
                            class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition disabled:opacity-50"
                        >
                            {{ cancelLoading ? 'Cancelling...' : 'Cancel Booking' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
