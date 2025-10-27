<script setup>
import { ref } from 'vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Sites/Admin/Layout.vue';

const props = defineProps({
    order: Object,
});

const showRefundModal = ref(false);
const refundForm = useForm({
    amount: props.order.total_raw,
    reason: '',
});

const getStatusClass = (status) => {
    const classes = {
        pending: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
        paid: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        cancelled: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
        refunded: 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
        succeeded: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        failed: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
    };
    return classes[status] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
};

const updateStatus = (status) => {
    if (confirm(`Are you sure you want to mark this order as ${status}?`)) {
        router.patch(route('admin.orders.updateStatus', props.order.id), {
            status: status,
        });
    }
};

const refundOrder = () => {
    refundForm.post(route('admin.orders.refund', props.order.id), {
        onSuccess: () => {
            showRefundModal.value = false;
            refundForm.reset();
        },
    });
};
</script>

<template>
    <Head title="Order Details" />

    <AdminLayout>
        <!-- Header -->
        <div class="p-4 bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <Link
                        :href="route('admin.orders.index')"
                        class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                    >
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                        </svg>
                        Back to Orders
                    </Link>
                    <h1 class="mt-2 text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">
                        Order {{ order.order_number }}
                    </h1>
                </div>
                <span
                    :class="getStatusClass(order.status)"
                    class="text-xs font-medium px-2.5 py-0.5 rounded"
                >
                    {{ order.status_formatted }}
                </span>
            </div>
        </div>

        <div class="p-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-4">
                    <!-- Order Items -->
                    <div class="bg-white shadow dark:bg-gray-800 sm:rounded-lg">
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Order Items</h3>
                        </div>
                        <div class="divide-y divide-gray-200 dark:divide-gray-700">
                            <div
                                v-for="item in order.items"
                                :key="item.id"
                                class="p-4"
                            >
                                <div class="flex justify-between">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            {{ item.orderable_type.split('\\').pop() }}
                                            <span v-if="item.orderable" class="text-sm text-gray-500 dark:text-gray-400">
                                                (#{{ item.orderable_id }})
                                            </span>
                                        </p>

                                        <!-- Booking Details -->
                                        <div v-if="item.orderable && item.orderable.type === 'booking'" class="mt-2 text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                            <p>Date: {{ item.orderable.booking_date }}</p>
                                            <p>Time: {{ item.orderable.start_time }} - {{ item.orderable.end_time }}</p>
                                            <p>Staff: {{ item.orderable.staff_name }}</p>
                                            <p>Status: {{ item.orderable.status }}</p>
                                        </div>

                                        <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                            <p>Quantity: {{ item.quantity }}</p>
                                            <p>Unit Price: ${{ item.unit_price }}</p>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <p class="font-semibold text-gray-900 dark:text-white">
                                            ${{ item.total_price }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Totals -->
                        <div class="p-4 border-t border-gray-200 bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Subtotal:</span>
                                    <span class="text-gray-900 dark:text-white">${{ order.subtotal }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-400">Tax:</span>
                                    <span class="text-gray-900 dark:text-white">${{ order.tax }}</span>
                                </div>
                                <div class="flex justify-between text-lg font-semibold pt-2 border-t border-gray-200 dark:border-gray-600">
                                    <span class="text-gray-900 dark:text-white">Total:</span>
                                    <span class="text-gray-900 dark:text-white">${{ order.total }} {{ order.currency }}</span>
                                </div>
                            </div>
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
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ order.customer_name }}</dd>
                                </div>

                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                    <dd class="mt-1 text-sm">
                                        <a :href="`mailto:${order.customer_email}`" class="text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                            {{ order.customer_email }}
                                        </a>
                                    </dd>
                                </div>

                                <div v-if="order.customer_phone" class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</dt>
                                    <dd class="mt-1 text-sm">
                                        <a :href="`tel:${order.customer_phone}`" class="text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                            {{ order.customer_phone }}
                                        </a>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div v-if="order.payment" class="bg-white shadow dark:bg-gray-800 sm:rounded-lg">
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Payment Information</h3>
                        </div>
                        <div class="p-4">
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment Gateway</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white capitalize">{{ order.payment.payment_gateway }}</dd>
                                </div>

                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Transaction ID</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono text-xs">
                                        {{ order.payment.gateway_transaction_id }}
                                    </dd>
                                </div>

                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment Method</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white capitalize">{{ order.payment.payment_method }}</dd>
                                </div>

                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Amount</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                        ${{ order.payment.amount }} {{ order.payment.currency }}
                                    </dd>
                                </div>

                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                    <dd class="mt-1">
                                        <span
                                            :class="getStatusClass(order.payment.status)"
                                            class="text-xs font-medium px-2.5 py-0.5 rounded"
                                        >
                                            {{ order.payment.status_formatted }}
                                        </span>
                                    </dd>
                                </div>

                                <div v-if="order.payment.processed_at" class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Processed At</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ order.payment.processed_at }}</dd>
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
                                v-if="order.status === 'pending'"
                                @click="updateStatus('paid')"
                                class="w-full inline-flex justify-center items-center px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 dark:bg-green-500 dark:hover:bg-green-600 dark:focus:ring-green-800"
                            >
                                Mark as Paid
                            </button>

                            <button
                                v-if="order.status === 'paid' && order.payment"
                                @click="showRefundModal = true"
                                class="w-full inline-flex justify-center items-center px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-purple-600 hover:bg-purple-700 focus:ring-4 focus:ring-purple-300 dark:bg-purple-500 dark:hover:bg-purple-600 dark:focus:ring-purple-800"
                            >
                                Process Refund
                            </button>

                            <button
                                v-if="order.status === 'pending'"
                                @click="updateStatus('cancelled')"
                                class="w-full inline-flex justify-center items-center px-3 py-2 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-800 focus:ring-4 focus:ring-red-300 dark:focus:ring-red-900"
                            >
                                Cancel Order
                            </button>
                        </div>
                    </div>

                    <!-- Order Details -->
                    <div class="bg-white shadow dark:bg-gray-800 sm:rounded-lg">
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Order Details</h3>
                        </div>
                        <div class="p-4 space-y-3">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Order Type</p>
                                <p class="text-sm text-gray-900 dark:text-white capitalize">{{ order.order_type }}</p>
                            </div>

                            <div v-if="order.company">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Company</p>
                                <p class="text-sm text-gray-900 dark:text-white">{{ order.company.name }}</p>
                            </div>

                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Created</p>
                                <p class="text-sm text-gray-900 dark:text-white">{{ order.created_at_formatted }}</p>
                            </div>

                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</p>
                                <p class="text-sm text-gray-900 dark:text-white">{{ order.updated_at }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Refund Modal -->
        <div v-if="showRefundModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 dark:bg-opacity-80 flex items-center justify-center z-50 p-4">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-800 max-w-md w-full">
                <div class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Process Refund
                    </h3>
                    <button
                        @click="showRefundModal = false"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-700 dark:hover:text-white"
                    >
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="refundOrder">
                    <div class="p-6 space-y-6">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Refund Amount
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 dark:text-gray-400">$</span>
                                <input
                                    v-model="refundForm.amount"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    :max="order.total_raw"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-8 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                    required
                                />
                            </div>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Maximum: ${{ order.total }}</p>
                            <p v-if="refundForm.errors.amount" class="mt-2 text-sm text-red-600 dark:text-red-500">
                                {{ refundForm.errors.amount }}
                            </p>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Reason (Optional)
                            </label>
                            <textarea
                                v-model="refundForm.reason"
                                rows="4"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                placeholder="Enter reason for refund..."
                            ></textarea>
                            <p v-if="refundForm.errors.reason" class="mt-2 text-sm text-red-600 dark:text-red-500">
                                {{ refundForm.errors.reason }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-700">
                        <button
                            type="submit"
                            :disabled="refundForm.processing"
                            class="text-white bg-purple-600 hover:bg-purple-700 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-purple-500 dark:hover:bg-purple-600 dark:focus:ring-purple-800 disabled:opacity-50"
                        >
                            Process Refund
                        </button>
                        <button
                            type="button"
                            @click="showRefundModal = false"
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
