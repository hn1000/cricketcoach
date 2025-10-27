<script setup>
import AdminLayout from '@/Layouts/Sites/Admin/Layout.vue';
import { Head, useForm, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    company: Object,
    staff: Object,
    exception: Object,
});

const page = usePage();
const exceptionData = page.props.exception.data || page.props.exception;

const form = useForm({
    exception_date: exceptionData.exception_date,
    start_time: exceptionData.start_time || '',
    end_time: exceptionData.end_time || '',
    type: exceptionData.type,
    reason: exceptionData.reason || '',
    notes: exceptionData.notes || '',
});

const isFullDayBlock = computed(() => {
    return form.type === 'unavailable' && !form.start_time && !form.end_time;
});

const submit = () => {
    form.put(route('admin.companies.staff.exceptions.update', [props.company.id, props.staff.id, exceptionData.id]));
};
</script>

<template>
    <Head :title="`Edit Exception - ${staff.first_name} ${staff.last_name}`" />

    <AdminLayout>
        <div class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <div class="w-full mb-1">
                <nav class="flex mb-5" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2">
                        <li class="inline-flex items-center">
                            <Link :href="route('admin.companies.index')" class="text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-white">
                                Companies
                            </Link>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <Link :href="route('admin.companies.staff.exceptions.index', [company.id, staff.id])" class="ml-1 text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-300 dark:hover:text-white">
                                    {{ staff.first_name }} {{ staff.last_name }}
                                </Link>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-gray-500 md:ml-2 dark:text-gray-400">Edit Exception</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Edit Availability Exception</h1>
            </div>
        </div>

        <div class="p-4">
            <form @submit.prevent="submit">
                <div class="grid gap-4 mb-4 sm:grid-cols-2">
                    <div>
                        <label for="exception_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date *</label>
                        <input
                            v-model="form.exception_date"
                            type="date"
                            id="exception_date"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            required
                        >
                        <div v-if="form.errors.exception_date" class="text-sm text-red-600 mt-1">{{ form.errors.exception_date }}</div>
                    </div>

                    <div>
                        <label for="type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Type *</label>
                        <select
                            v-model="form.type"
                            id="type"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            required
                        >
                            <option value="unavailable">Unavailable</option>
                            <option value="custom_hours">Custom Hours</option>
                        </select>
                        <div v-if="form.errors.type" class="text-sm text-red-600 mt-1">{{ form.errors.type }}</div>
                    </div>

                    <div>
                        <label for="start_time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Start Time <span v-if="form.type === 'unavailable'" class="text-gray-500">(Optional - leave empty for full day)</span>
                        </label>
                        <input
                            v-model="form.start_time"
                            type="time"
                            id="start_time"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        >
                        <div v-if="form.errors.start_time" class="text-sm text-red-600 mt-1">{{ form.errors.start_time }}</div>
                    </div>

                    <div>
                        <label for="end_time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            End Time <span v-if="form.type === 'unavailable'" class="text-gray-500">(Optional - leave empty for full day)</span>
                        </label>
                        <input
                            v-model="form.end_time"
                            type="time"
                            id="end_time"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        >
                        <div v-if="form.errors.end_time" class="text-sm text-red-600 mt-1">{{ form.errors.end_time }}</div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="reason" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Reason</label>
                        <input
                            v-model="form.reason"
                            type="text"
                            id="reason"
                            maxlength="255"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            placeholder="e.g., Vacation, Holiday, Meeting, etc."
                        >
                        <div v-if="form.errors.reason" class="text-sm text-red-600 mt-1">{{ form.errors.reason }}</div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="notes" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Notes</label>
                        <textarea
                            v-model="form.notes"
                            id="notes"
                            rows="3"
                            maxlength="1000"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            placeholder="Add any additional notes about this exception..."
                        ></textarea>
                        <div v-if="form.errors.notes" class="text-sm text-red-600 mt-1">{{ form.errors.notes }}</div>
                    </div>
                </div>

                <div v-if="isFullDayBlock" class="p-4 mb-4 bg-red-50 border border-red-200 rounded-lg dark:bg-red-900 dark:border-red-700">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-700 dark:text-red-300 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="text-sm text-red-700 dark:text-red-300">
                            <p class="font-medium">This will block the entire day</p>
                            <p class="mt-1">No booking slots will be available for this date.</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 disabled:opacity-50"
                    >
                        Update Exception
                    </button>
                    <Link
                        :href="route('admin.companies.staff.exceptions.index', [company.id, staff.id])"
                        class="text-gray-600 inline-flex items-center hover:text-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:text-gray-400 dark:hover:text-white dark:focus:ring-gray-800"
                    >
                        Cancel
                    </Link>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
