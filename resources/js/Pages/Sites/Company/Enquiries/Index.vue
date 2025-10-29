<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import CompanyLayout from '@/Layouts/Sites/Company/Layout.vue';

const props = defineProps({
    enquiries: Object,
    filters: Object,
});

const filters = ref({
    status: props.filters?.status || '',
});

const applyFilters = () => {
    router.get(route('company.enquiries.index'), filters.value, {
        preserveState: true,
        preserveScroll: true,
    });
};

const getStatusClass = (status) => {
    const classes = {
        new: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
        read: 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
        replied: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        archived: 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
    };
    return classes[status] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};
</script>

<template>
    <Head title="Enquiries" />

    <CompanyLayout>
        <div class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700 mb-4 rounded-lg">
            <div class="w-full mb-1">
                <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Coaching Enquiries</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Manage customer enquiries and messages
                </p>
            </div>
        </div>

        <!-- Filters Panel -->
        <div class="p-4 bg-gray-50 border border-gray-200 dark:bg-gray-700 dark:border-gray-600 mb-4 rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Status
                    </label>
                    <select
                        v-model="filters.status"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        @change="applyFilters"
                    >
                        <option value="">All Statuses</option>
                        <option value="new">New</option>
                        <option value="read">Read</option>
                        <option value="replied">Replied</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="flex flex-col">
            <div class="overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden shadow rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Status
                                    </th>
                                    <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Customer
                                    </th>
                                    <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Coach
                                    </th>
                                    <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Message Preview
                                    </th>
                                    <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Date
                                    </th>
                                    <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                <tr v-if="enquiries.data.length === 0">
                                    <td colspan="6" class="p-4 text-center text-gray-500 dark:text-gray-400">
                                        <div class="py-12">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                            </svg>
                                            <p class="mt-4 text-lg font-medium">No enquiries found</p>
                                            <p class="mt-2 text-sm">Try adjusting your filters</p>
                                        </div>
                                    </td>
                                </tr>
                                <tr
                                    v-for="enquiry in enquiries.data"
                                    :key="enquiry.id"
                                    class="hover:bg-gray-50 dark:hover:bg-gray-700"
                                    :class="{ 'bg-blue-50 dark:bg-blue-900/20': enquiry.status === 'new' }"
                                >
                                    <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">
                                        <span :class="getStatusClass(enquiry.status)" class="px-2 py-1 text-xs font-medium rounded-full uppercase">
                                            {{ enquiry.status }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-base font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <div>{{ enquiry.user.name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ enquiry.user.email }}</div>
                                    </td>
                                    <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">
                                        {{ enquiry.staff.first_name }} {{ enquiry.staff.last_name }}
                                    </td>
                                    <td class="p-4 text-sm font-normal text-gray-500 max-w-xs truncate dark:text-gray-400">
                                        {{ enquiry.message.substring(0, 80) }}...
                                    </td>
                                    <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">
                                        {{ formatDate(enquiry.created_at) }}
                                    </td>
                                    <td class="p-4 space-x-2 whitespace-nowrap">
                                        <Link
                                            :href="route('company.enquiries.show', enquiry.id)"
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700"
                                        >
                                            View
                                        </Link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div v-if="enquiries.links.length > 3" class="sticky bottom-0 right-0 items-center w-full p-4 bg-white border-t border-gray-200 sm:flex sm:justify-between dark:bg-gray-800 dark:border-gray-700 mt-4 rounded-lg">
            <div class="flex items-center mb-4 sm:mb-0">
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                    Showing
                    <span class="font-semibold text-gray-900 dark:text-white">{{ enquiries.from }}-{{ enquiries.to }}</span>
                    of
                    <span class="font-semibold text-gray-900 dark:text-white">{{ enquiries.total }}</span>
                </span>
            </div>
            <div class="flex items-center space-x-3">
                <Link
                    v-for="(link, index) in enquiries.links"
                    :key="index"
                    :href="link.url || '#'"
                    :class="{
                        'bg-green-700 text-white hover:bg-green-800': link.active,
                        'text-gray-500 bg-white hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white': !link.active,
                        'cursor-not-allowed opacity-50': !link.url,
                    }"
                    class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium border border-gray-300 rounded-lg dark:border-gray-700"
                    v-html="link.label"
                />
            </div>
        </div>
    </CompanyLayout>
</template>
