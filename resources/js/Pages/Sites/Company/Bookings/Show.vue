<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import CompanyLayout from '@/Layouts/Sites/Company/Layout.vue';

const props = defineProps({
    booking: Object,
});

const getStatusClass = (status) => {
    const classes = {
        pending_payment: 'bg-yellow-100 text-yellow-800',
        confirmed: 'bg-blue-100 text-blue-800',
        completed: 'bg-green-100 text-green-800',
        cancelled: 'bg-red-100 text-red-800',
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
};

const updateStatus = (status) => {
    if (confirm(`Are you sure you want to mark this booking as ${status}?`)) {
        router.patch(route('company.bookings.update-status', props.booking.id), { status });
    }
};
</script>

<template>
    <Head title="Booking Details" />

    <CompanyLayout>
        <div class="mb-4">
            <Link
                :href="route('company.bookings.index')"
                class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700"
            >
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                </svg>
                Back to Bookings
            </Link>
            <div class="mt-2 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Booking #{{ booking.id }}</h1>
                <span :class="getStatusClass(booking.status)" class="px-3 py-1 text-sm font-medium rounded-full">
                    {{ booking.status.replace('_', ' ') }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Main Details -->
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Booking Details</h3>
                    <dl class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm text-gray-500">Company</dt>
                            <dd class="mt-1 font-medium">{{ booking.company.name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Coach</dt>
                            <dd class="mt-1 font-medium">{{ booking.staff.first_name }} {{ booking.staff.last_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Date</dt>
                            <dd class="mt-1 font-medium">{{ new Date(booking.booking_date).toLocaleDateString() }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Time</dt>
                            <dd class="mt-1 font-medium">{{ booking.start_time.substring(0,5) }} - {{ booking.end_time.substring(0,5) }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Customer Information</h3>
                    <dl class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm text-gray-500">Name</dt>
                            <dd class="mt-1 font-medium">{{ booking.user.name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Email</dt>
                            <dd class="mt-1">
                                <a :href="`mailto:${booking.user.email}`" class="text-green-600 hover:underline">{{ booking.user.email }}</a>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Sidebar Actions -->
            <div class="space-y-4">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Actions</h3>
                    <div class="space-y-2">
                        <button
                            v-if="booking.status === 'pending_payment'"
                            @click="updateStatus('confirmed')"
                            class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg"
                        >
                            Mark as Confirmed
                        </button>
                        <button
                            v-if="booking.status === 'confirmed'"
                            @click="updateStatus('completed')"
                            class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg"
                        >
                            Mark as Completed
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </CompanyLayout>
</template>
