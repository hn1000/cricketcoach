<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import CompanyLayout from '@/Layouts/Sites/Company/Layout.vue';

const props = defineProps({
    enquiry: Object,
});

const getStatusClass = (status) => {
    const classes = {
        new: 'bg-blue-100 text-blue-800',
        read: 'bg-gray-100 text-gray-800',
        replied: 'bg-green-100 text-green-800',
        archived: 'bg-purple-100 text-purple-800',
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
};

const updateStatus = (status) => {
    if (confirm(`Mark this enquiry as ${status}?`)) {
        router.patch(route('company.enquiries.update-status', props.enquiry.id), { status });
    }
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
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

    <CompanyLayout>
        <div class="mb-4">
            <Link
                :href="route('company.enquiries.index')"
                class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700"
            >
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                </svg>
                Back to Enquiries
            </Link>
            <div class="mt-2 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900">Enquiry #{{ enquiry.id }}</h1>
                <span :class="getStatusClass(enquiry.status)" class="px-3 py-1 text-sm font-medium rounded-full uppercase">
                    {{ enquiry.status }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Customer Message</h3>
                    <div class="bg-gray-50 rounded-lg p-6">
                        <p class="whitespace-pre-line leading-relaxed">{{ enquiry.message }}</p>
                    </div>
                </div>

                <div v-if="enquiry.preferred_date || enquiry.preferred_time_slot" class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Customer Preferences</h3>
                    <dl class="grid grid-cols-2 gap-4">
                        <div v-if="enquiry.preferred_date">
                            <dt class="text-sm text-gray-500">Preferred Date</dt>
                            <dd class="mt-1 font-medium">{{ formatDate(enquiry.preferred_date) }}</dd>
                        </div>
                        <div v-if="enquiry.preferred_time_slot">
                            <dt class="text-sm text-gray-500">Preferred Time</dt>
                            <dd class="mt-1 font-medium">{{ timeSlotLabels[enquiry.preferred_time_slot] }}</dd>
                        </div>
                    </dl>
                    <p class="mt-4 text-sm text-blue-700 bg-blue-50 p-3 rounded">
                        <strong>Note:</strong> These are preferences only - contact the customer to arrange the actual session time.
                    </p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Customer Details</h3>
                    <dl class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm text-gray-500">Name</dt>
                            <dd class="mt-1 font-medium">{{ enquiry.user.name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Email</dt>
                            <dd class="mt-1">
                                <a :href="`mailto:${enquiry.user.email}`" class="text-green-600 hover:underline">
                                    {{ enquiry.user.email }}
                                </a>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-4">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Status</h3>
                    <div class="space-y-2">
                        <button
                            v-if="enquiry.status !== 'read'"
                            @click="updateStatus('read')"
                            class="w-full px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg"
                        >
                            Mark as Read
                        </button>
                        <button
                            v-if="enquiry.status !== 'replied'"
                            @click="updateStatus('replied')"
                            class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg"
                        >
                            Mark as Replied
                        </button>
                        <button
                            v-if="enquiry.status !== 'archived'"
                            @click="updateStatus('archived')"
                            class="w-full px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg"
                        >
                            Archive
                        </button>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Booking Info</h3>
                    <dl class="space-y-3 text-sm">
                        <div>
                            <dt class="text-gray-500">Company</dt>
                            <dd class="mt-1 font-medium">{{ enquiry.company.name }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Coach</dt>
                            <dd class="mt-1 font-medium">{{ enquiry.staff.first_name }} {{ enquiry.staff.last_name }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg shadow border-2 border-green-200 p-6">
                    <h3 class="text-lg font-semibold mb-3">Quick Actions</h3>
                    <div class="space-y-2">
                        <a
                            :href="`mailto:${enquiry.user.email}?subject=Re: Your Coaching Enquiry&body=Hi ${enquiry.user.name},%0D%0A%0D%0AThank you for your enquiry about coaching with ${enquiry.staff.first_name} ${enquiry.staff.last_name}.%0D%0A%0D%0A`"
                            class="block w-full px-4 py-2 bg-green-700 hover:bg-green-800 text-white text-center rounded-lg"
                        >
                            Email Customer
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </CompanyLayout>
</template>
