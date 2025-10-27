<script setup>
import { ref } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Sites/Admin/Layout.vue';

const props = defineProps({
    booking: Object,
});

const showCancelModal = ref(false);
const cancelForm = useForm({
    cancellation_reason: '',
});

const getStatusClass = (status) => {
    const classes = {
        pending_payment: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
        confirmed: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
        completed: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        cancelled: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
        pending: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
        paid: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        succeeded: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        failed: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
    };
    return classes[status] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
};

const updateStatus = (status) => {
    if (confirm(`Are you sure you want to mark this booking as ${status}?`)) {
        router.patch(route('admin.bookings.updateStatus', props.booking.id), {
            status: status,
        });
    }
};

const cancelBooking = () => {
    cancelForm.post(route('admin.bookings.cancel', props.booking.id), {
        onSuccess: () => {
            showCancelModal.value = false;
            cancelForm.reset();
        },
    });
};

const resendConfirmation = () => {
    if (confirm('Resend confirmation email to customer?')) {
        router.post(route('admin.bookings.resendConfirmation', props.booking.id));
    }
};
</script>

<template>
    <Head title="Booking Details" />

    <AdminLayout>
        <!-- Header -->
        <div class="p-4 bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <Link
                        :href="route('admin.bookings.index')"
                        class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                    >
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                        </svg>
                        Back to Bookings
                    </Link>
                    <h1 class="mt-2 text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">
                        Booking #{{ booking.id }}
                    </h1>
                </div>
                <span
                    :class="getStatusClass(booking.status)"
                    class="text-xs font-medium px-2.5 py-0.5 rounded"
                >
                    {{ booking.status_formatted }}
                </span>
            </div>
        </div>

        <div class="p-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-4">
                    <!-- Booking Details -->
                    <div class="bg-white shadow dark:bg-gray-800 sm:rounded-lg">
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Booking Details</h3>
                        </div>
                        <div class="p-4">
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Company</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ booking.company.name }}</dd>
                                </div>

                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Staff Member</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                        {{ booking.staff.name }}
                                        <span v-if="booking.staff.position" class="text-gray-500 dark:text-gray-400">
                                            ({{ booking.staff.position }})
                                        </span>
                                    </dd>
                                </div>

                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ booking.booking_date_formatted }}</dd>
                                </div>

                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Time</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                        {{ booking.start_time }} - {{ booking.end_time }}
                                    </dd>
                                </div>

                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Duration</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ booking.duration_formatted }}</dd>
                                </div>

                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Price</dt>
                                    <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">${{ booking.price }}</dd>
                                </div>

                                <div v-if="booking.notes" class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Notes</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ booking.notes }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="bg-white shadow dark:bg-gray-800 sm:rounded-lg">
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Customer Information</h3>
                        </div>
                        <div class="p-4">
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ booking.customer_name }}</dd>
                                </div>

                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                    <dd class="mt-1 text-sm">
                                        <a :href="`mailto:${booking.customer_email}`" class="text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                            {{ booking.customer_email }}
                                        </a>
                                    </dd>
                                </div>

                                <div v-if="booking.customer_phone" class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</dt>
                                    <dd class="mt-1 text-sm">
                                        <a :href="`tel:${booking.customer_phone}`" class="text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                            {{ booking.customer_phone }}
                                        </a>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Order Information -->
                    <div v-if="booking.order" class="bg-white shadow dark:bg-gray-800 sm:rounded-lg">
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Order Information</h3>
                        </div>
                        <div class="p-4">
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Order Number</dt>
                                    <dd class="mt-1 text-sm">
                                        <Link
                                            :href="route('admin.orders.show', booking.order.id)"
                                            class="text-blue-600 hover:text-blue-700 dark:text-blue-400"
                                        >
                                            {{ booking.order.order_number }}
                                        </Link>
                                    </dd>
                                </div>

                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Order Status</dt>
                                    <dd class="mt-1">
                                        <span
                                            :class="getStatusClass(booking.order.status)"
                                            class="text-xs font-medium px-2.5 py-0.5 rounded"
                                        >
                                            {{ booking.order.status }}
                                        </span>
                                    </dd>
                                </div>

                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">${{ booking.order.total }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div v-if="booking.payment_status" class="bg-white shadow dark:bg-gray-800 sm:rounded-lg">
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Payment Information</h3>
                        </div>
                        <div class="p-4">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment Status</dt>
                                <dd class="mt-1">
                                    <span
                                        :class="getStatusClass(booking.payment_status)"
                                        class="text-xs font-medium px-2.5 py-0.5 rounded"
                                    >
                                        {{ booking.payment_status }}
                                    </span>
                                </dd>
                            </dl>
                        </div>
                    </div>

                    <!-- Cancellation Information -->
                    <div v-if="booking.cancelled_at" class="bg-white shadow dark:bg-gray-800 sm:rounded-lg">
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Cancellation Information</h3>
                        </div>
                        <div class="p-4">
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Cancelled At</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ booking.cancelled_at }}</dd>
                                </div>

                                <div v-if="booking.cancellation_reason">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Reason</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ booking.cancellation_reason }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-4">
                    <!-- Actions -->
                    <div class="bg-white shadow dark:bg-gray-800 sm:rounded-lg">
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Actions</h3>
                        </div>
                        <div class="p-4 space-y-3">
                            <button
                                v-if="booking.status === 'pending_payment'"
                                @click="updateStatus('confirmed')"
                                class="w-full inline-flex justify-center items-center px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                            >
                                Mark as Confirmed
                            </button>

                            <button
                                v-if="booking.status === 'confirmed'"
                                @click="updateStatus('completed')"
                                class="w-full inline-flex justify-center items-center px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 dark:bg-green-500 dark:hover:bg-green-600 dark:focus:ring-green-800"
                            >
                                Mark as Completed
                            </button>

                            <button
                                v-if="['pending_payment', 'confirmed'].includes(booking.status)"
                                @click="showCancelModal = true"
                                class="w-full inline-flex justify-center items-center px-3 py-2 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-800 focus:ring-4 focus:ring-red-300 dark:focus:ring-red-900"
                            >
                                Cancel Booking
                            </button>

                            <button
                                @click="resendConfirmation"
                                class="w-full inline-flex justify-center items-center px-3 py-2 text-sm font-medium text-center text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-primary-300 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                            >
                                Resend Confirmation
                            </button>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="bg-white shadow dark:bg-gray-800 sm:rounded-lg">
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Timeline</h3>
                        </div>
                        <div class="p-4 space-y-3">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Created</p>
                                <p class="text-sm text-gray-900 dark:text-white">{{ booking.created_at }}</p>
                            </div>

                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</p>
                                <p class="text-sm text-gray-900 dark:text-white">{{ booking.updated_at }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cancel Modal -->
        <div v-if="showCancelModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 dark:bg-opacity-80 flex items-center justify-center z-50 p-4">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-800 max-w-md w-full">
                <div class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Cancel Booking
                    </h3>
                    <button
                        @click="showCancelModal = false"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-700 dark:hover:text-white"
                    >
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="cancelBooking">
                    <div class="p-6 space-y-6">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Cancellation Reason
                            </label>
                            <textarea
                                v-model="cancelForm.cancellation_reason"
                                rows="4"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                placeholder="Please provide a reason for cancellation..."
                                required
                            ></textarea>
                            <p v-if="cancelForm.errors.cancellation_reason" class="mt-2 text-sm text-red-600 dark:text-red-500">
                                {{ cancelForm.errors.cancellation_reason }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-700">
                        <button
                            type="submit"
                            :disabled="cancelForm.processing"
                            class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:focus:ring-red-900 disabled:opacity-50"
                        >
                            Cancel Booking
                        </button>
                        <button
                            type="button"
                            @click="showCancelModal = false"
                            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600"
                        >
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
