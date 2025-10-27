<script setup>
import AdminLayout from '@/Layouts/Sites/Admin/Layout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    company: Object,
    staff: Object,
});

const form = useForm({
    day_of_week: 'monday',
    start_time: '09:00',
    end_time: '17:00',
    slot_duration: 45,
    is_active: true,
    effective_from: '',
    effective_until: '',
    notes: '',
});

const submit = () => {
    form.post(route('admin.companies.staff.availability.store', [props.company.id, props.staff.id]));
};
</script>

<template>
    <Head :title="`Add Availability Template - ${staff.first_name} ${staff.last_name}`" />

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
                                <Link :href="route('admin.companies.staff.availability.index', [company.id, staff.id])" class="ml-1 text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-300 dark:hover:text-white">
                                    {{ staff.first_name }} {{ staff.last_name }}
                                </Link>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-gray-500 md:ml-2 dark:text-gray-400">Add Availability Template</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Add Availability Template</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Create a recurring weekly schedule that defines when this staff member is available for bookings.
                </p>
            </div>
        </div>

        <div class="p-4">
            <form @submit.prevent="submit">
                <div class="grid gap-4 mb-4 sm:grid-cols-2">
                    <div>
                        <label for="day_of_week" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Day of Week *</label>
                        <select
                            v-model="form.day_of_week"
                            id="day_of_week"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            required
                        >
                            <option value="monday">Monday</option>
                            <option value="tuesday">Tuesday</option>
                            <option value="wednesday">Wednesday</option>
                            <option value="thursday">Thursday</option>
                            <option value="friday">Friday</option>
                            <option value="saturday">Saturday</option>
                            <option value="sunday">Sunday</option>
                        </select>
                        <div v-if="form.errors.day_of_week" class="text-sm text-red-600 mt-1">{{ form.errors.day_of_week }}</div>
                    </div>

                    <div>
                        <label for="slot_duration" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Slot Duration (minutes) *</label>
                        <select
                            v-model="form.slot_duration"
                            id="slot_duration"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            required
                        >
                            <option :value="15">15 minutes</option>
                            <option :value="30">30 minutes</option>
                            <option :value="45">45 minutes</option>
                            <option :value="60">60 minutes (1 hour)</option>
                            <option :value="90">90 minutes (1.5 hours)</option>
                            <option :value="120">120 minutes (2 hours)</option>
                        </select>
                        <div v-if="form.errors.slot_duration" class="text-sm text-red-600 mt-1">{{ form.errors.slot_duration }}</div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            How long each booking slot should be. You can create multiple templates for the same day with different durations.
                        </p>
                    </div>

                    <div>
                        <label for="start_time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start Time *</label>
                        <input
                            v-model="form.start_time"
                            type="time"
                            id="start_time"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            required
                        >
                        <div v-if="form.errors.start_time" class="text-sm text-red-600 mt-1">{{ form.errors.start_time }}</div>
                    </div>

                    <div>
                        <label for="end_time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End Time *</label>
                        <input
                            v-model="form.end_time"
                            type="time"
                            id="end_time"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            required
                        >
                        <div v-if="form.errors.end_time" class="text-sm text-red-600 mt-1">{{ form.errors.end_time }}</div>
                    </div>

                    <div>
                        <label for="effective_from" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Effective From (Optional)</label>
                        <input
                            v-model="form.effective_from"
                            type="date"
                            id="effective_from"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        >
                        <div v-if="form.errors.effective_from" class="text-sm text-red-600 mt-1">{{ form.errors.effective_from }}</div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Leave empty to start immediately
                        </p>
                    </div>

                    <div>
                        <label for="effective_until" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Effective Until (Optional)</label>
                        <input
                            v-model="form.effective_until"
                            type="date"
                            id="effective_until"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        >
                        <div v-if="form.errors.effective_until" class="text-sm text-red-600 mt-1">{{ form.errors.effective_until }}</div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Leave empty for no end date
                        </p>
                    </div>

                    <div>
                        <label for="is_active" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                        <select
                            v-model="form.is_active"
                            id="is_active"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        >
                            <option :value="true">Active</option>
                            <option :value="false">Inactive</option>
                        </select>
                        <div v-if="form.errors.is_active" class="text-sm text-red-600 mt-1">{{ form.errors.is_active }}</div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="notes" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Notes</label>
                        <textarea
                            v-model="form.notes"
                            id="notes"
                            rows="3"
                            maxlength="1000"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            placeholder="Add any notes about this availability template..."
                        ></textarea>
                        <div v-if="form.errors.notes" class="text-sm text-red-600 mt-1">{{ form.errors.notes }}</div>
                    </div>
                </div>

                <div class="p-4 mb-4 bg-blue-50 border border-blue-200 rounded-lg dark:bg-blue-900 dark:border-blue-700">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-700 dark:text-blue-300 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="text-sm text-blue-700 dark:text-blue-300">
                            <p class="font-medium mb-1">How to handle different slot durations:</p>
                            <ul class="list-disc ml-4 space-y-1">
                                <li>Create separate templates for different time periods (e.g., 1 hour slots 9-12, 30 min slots 2-5)</li>
                                <li>Each template generates booking slots automatically based on the duration</li>
                                <li>You can create multiple templates for the same day with non-overlapping times</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 disabled:opacity-50"
                    >
                        Add Availability Template
                    </button>
                    <Link
                        :href="route('admin.companies.staff.availability.index', [company.id, staff.id])"
                        class="text-gray-600 inline-flex items-center hover:text-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:text-gray-400 dark:hover:text-white dark:focus:ring-gray-800"
                    >
                        Cancel
                    </Link>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
