<script setup>
import AdminLayout from '@/Layouts/Sites/Admin/Layout.vue';
import { Head, useForm, Link, usePage } from '@inertiajs/vue3';

const props = defineProps({
    company: Object,
    staff: Object,
    template: Object,
});

const page = usePage();
const templateData = page.props.template.data || page.props.template;

const form = useForm({
    day_of_week: templateData.day_of_week,
    start_time: templateData.start_time,
    end_time: templateData.end_time,
    slot_duration: templateData.slot_duration,
    is_active: templateData.is_active,
    effective_from: templateData.effective_from || '',
    effective_until: templateData.effective_until || '',
    notes: templateData.notes || '',
});

const submit = () => {
    form.put(route('admin.companies.staff.availability.update', [props.company.id, props.staff.id, templateData.id]));
};
</script>

<template>
    <Head :title="`Edit Availability Template - ${staff.first_name} ${staff.last_name}`" />

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
                                <span class="ml-1 text-gray-500 md:ml-2 dark:text-gray-400">Edit Availability Template</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Edit Availability Template</h1>
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

                <div class="flex items-center space-x-4">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 disabled:opacity-50"
                    >
                        Update Availability Template
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
