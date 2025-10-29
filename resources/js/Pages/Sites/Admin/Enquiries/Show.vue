<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/Sites/Admin/Layout.vue';

const props = defineProps({
    enquiry: Object,
});

const getStatusClass = (status) => {
    const classes = {
        new: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
        read: 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
        replied: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        archived: 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
    };
    return classes[status] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
};

const updateStatus = (status) => {
    if (confirm(`Are you sure you want to mark this enquiry as ${status}?`)) {
        router.patch(route('admin.enquiries.update-status', props.enquiry.id), {
            status: status,
        }, {
            preserveScroll: true,
        });
    }
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};

const formatDateTime = (date) => {
    return new Date(date).toLocaleString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const timeSlotLabels = {
    morning: 'Morning (6am - 12pm)',
    afternoon: 'Afternoon (12pm - 6pm)',
    evening: 'Evening (6pm - 10pm)',
};
</script>

<template>
    <Head title="Enquiry Details" />

    <AdminLayout>
        <!-- Header -->
        <div class="p-4 bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <Link
                        :href="route('admin.enquiries.index')"
                        class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                    >
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                        </svg>
                        Back to Enquiries
                    </Link>
                    <h1 class="mt-2 text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">
                        Enquiry #{{ enquiry.id }}
                    </h1>
                </div>
                <span
                    :class="getStatusClass(enquiry.status)"
                    class="text-xs font-medium px-2.5 py-0.5 rounded uppercase"
                >
                    {{ enquiry.status }}
                </span>
            </div>
        </div>

        <div class="p-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-4">
                    <!-- Customer Message -->
                    <div class="bg-white shadow dark:bg-gray-800 sm:rounded-lg">
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Customer Message</h3>
                        </div>
                        <div class="p-6">
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                                <p class="text-gray-900 dark:text-white whitespace-pre-line leading-relaxed">
                                    {{ enquiry.message }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Preferences -->
                    <div v-if="enquiry.preferred_date || enquiry.preferred_time_slot" class="bg-white shadow dark:bg-gray-800 sm:rounded-lg">
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Customer Preferences</h3>
                        </div>
                        <div class="p-4">
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                                <div v-if="enquiry.preferred_date" class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Preferred Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ formatDate(enquiry.preferred_date) }}</dd>
                                </div>

                                <div v-if="enquiry.preferred_time_slot" class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Preferred Time Slot</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ timeSlotLabels[enquiry.preferred_time_slot] }}</dd>
                                </div>
                            </dl>
                            <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                <p class="text-sm text-blue-700 dark:text-blue-300">
                                    <strong>Note:</strong> These are the customer's preferences - not confirmed bookings. Contact the customer to arrange the actual session time.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Details -->
                    <div class="bg-white shadow dark:bg-gray-800 sm:rounded-lg">
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Customer Details</h3>
                        </div>
                        <div class="p-4">
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ enquiry.user.name }}</dd>
                                </div>

                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                        <a :href="`mailto:${enquiry.user.email}`" class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300">
                                            {{ enquiry.user.email }}
                                        </a>
                                    </dd>
                                </div>

                                <div v-if="enquiry.user.phone" class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                        <a :href="`tel:${enquiry.user.phone}`" class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300">
                                            {{ enquiry.user.phone }}
                                        </a>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-4">
                    <!-- Status Actions -->
                    <div class="bg-white shadow dark:bg-gray-800 sm:rounded-lg">
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Status</h3>
                        </div>
                        <div class="p-4 space-y-3">
                            <button
                                v-if="enquiry.status !== 'read'"
                                @click="updateStatus('read')"
                                class="w-full px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600"
                            >
                                Mark as Read
                            </button>

                            <button
                                v-if="enquiry.status !== 'replied'"
                                @click="updateStatus('replied')"
                                class="w-full px-4 py-2 text-sm font-medium text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700"
                            >
                                Mark as Replied
                            </button>

                            <button
                                v-if="enquiry.status !== 'archived'"
                                @click="updateStatus('archived')"
                                class="w-full px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600"
                            >
                                Archive
                            </button>

                            <button
                                v-if="enquiry.status === 'archived'"
                                @click="updateStatus('new')"
                                class="w-full px-4 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700"
                            >
                                Restore to New
                            </button>
                        </div>
                    </div>

                    <!-- Booking Details -->
                    <div class="bg-white shadow dark:bg-gray-800 sm:rounded-lg">
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Booking Info</h3>
                        </div>
                        <div class="p-4">
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Company</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ enquiry.company.name }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Coach</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                        {{ enquiry.staff.first_name }} {{ enquiry.staff.last_name }}
                                    </dd>
                                    <dd v-if="enquiry.staff.position" class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ enquiry.staff.position }}
                                    </dd>
                                </div>

                                <div v-if="enquiry.staff.email">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Coach Email</dt>
                                    <dd class="mt-1 text-sm">
                                        <a :href="`mailto:${enquiry.staff.email}`" class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300">
                                            {{ enquiry.staff.email }}
                                        </a>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="bg-white shadow dark:bg-gray-800 sm:rounded-lg">
                        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Timeline</h3>
                        </div>
                        <div class="p-4">
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Received</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ formatDateTime(enquiry.created_at) }}</dd>
                                </div>

                                <div v-if="enquiry.read_at">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Read</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ formatDateTime(enquiry.read_at) }}</dd>
                                </div>

                                <div v-if="enquiry.replied_at">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Replied</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ formatDateTime(enquiry.replied_at) }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Contact Actions -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 shadow sm:rounded-lg border-2 border-green-200 dark:border-green-800">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Quick Actions</h3>
                            <div class="space-y-2">
                                <a
                                    :href="`mailto:${enquiry.user.email}?subject=Re: Your Coaching Enquiry&body=Hi ${enquiry.user.name},%0D%0A%0D%0AThank you for your enquiry about coaching with ${enquiry.staff.first_name} ${enquiry.staff.last_name}.%0D%0A%0D%0A`"
                                    class="block w-full px-4 py-2 text-sm font-medium text-center text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700"
                                >
                                    Email Customer
                                </a>
                                <a
                                    v-if="enquiry.user.phone"
                                    :href="`tel:${enquiry.user.phone}`"
                                    class="block w-full px-4 py-2 text-sm font-medium text-center text-green-700 bg-white border-2 border-green-700 rounded-lg hover:bg-green-50 focus:ring-4 focus:ring-green-300 dark:text-green-300 dark:bg-gray-800 dark:border-green-600 dark:hover:bg-gray-700"
                                >
                                    Call Customer
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
