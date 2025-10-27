<script setup>
import AdminLayout from '@/Layouts/Sites/Admin/Layout.vue';
import { Head, useForm, Link, usePage } from '@inertiajs/vue3';

const props = defineProps({
    company: Object,
    staff: Object,
    slot: Object,
});

const page = usePage();
const bookingSlot = page.props.slot.data || page.props.slot;

const form = useForm({
    date: bookingSlot.date,
    start_time: bookingSlot.start_time,
    end_time: bookingSlot.end_time,
    duration: bookingSlot.duration,
    is_available: bookingSlot.is_available,
    status: bookingSlot.status,
    notes: bookingSlot.notes,
});

const submit = () => {
    form.put(route('admin.companies.staff.booking-slots.update', [props.company.id, props.staff.id, bookingSlot.id]));
};
</script>

<template>
    <Head :title="`Edit Booking Slot - ${staff.first_name} ${staff.last_name}`" />

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
                                <Link :href="route('admin.companies.staff.booking-slots.index', [company.id, staff.id])" class="ml-1 text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-300 dark:hover:text-white">
                                    {{ staff.first_name }} {{ staff.last_name }}
                                </Link>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-gray-500 md:ml-2 dark:text-gray-400">Edit Booking Slot</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Edit Booking Slot</h1>
            </div>
        </div>

        <div class="p-4">
            <div v-if="bookingSlot.status === 'booked'" class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg dark:bg-yellow-900 dark:border-yellow-700">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-yellow-700 dark:text-yellow-300 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-sm text-yellow-700 dark:text-yellow-300">
                        <strong>Warning:</strong> This slot is currently booked. Cancel the booking before making changes.
                    </p>
                </div>
            </div>

            <form @submit.prevent="submit">
                <div class="grid gap-4 mb-4 sm:grid-cols-2">
                    <div>
                        <label for="date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date *</label>
                        <input
                            v-model="form.date"
                            type="date"
                            id="date"
                            :disabled="bookingSlot.status === 'booked'"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white disabled:opacity-50"
                            required
                        >
                        <div v-if="form.errors.date" class="text-sm text-red-600 mt-1">{{ form.errors.date }}</div>
                    </div>

                    <div>
                        <label for="duration" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Duration (minutes) *</label>
                        <input
                            v-model="form.duration"
                            type="number"
                            id="duration"
                            min="15"
                            max="480"
                            step="15"
                            :disabled="bookingSlot.status === 'booked'"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white disabled:opacity-50"
                            required
                        >
                        <div v-if="form.errors.duration" class="text-sm text-red-600 mt-1">{{ form.errors.duration }}</div>
                    </div>

                    <div>
                        <label for="start_time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start Time *</label>
                        <input
                            v-model="form.start_time"
                            type="time"
                            id="start_time"
                            :disabled="bookingSlot.status === 'booked'"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white disabled:opacity-50"
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
                            :disabled="bookingSlot.status === 'booked'"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white disabled:opacity-50"
                            required
                        >
                        <div v-if="form.errors.end_time" class="text-sm text-red-600 mt-1">{{ form.errors.end_time }}</div>
                    </div>

                    <div>
                        <label for="status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status *</label>
                        <select
                            v-model="form.status"
                            id="status"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        >
                            <option value="available">Available</option>
                            <option value="blocked">Blocked</option>
                            <option value="completed">Completed</option>
                        </select>
                        <div v-if="form.errors.status" class="text-sm text-red-600 mt-1">{{ form.errors.status }}</div>
                    </div>

                    <div>
                        <label for="is_available" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Available for Booking</label>
                        <select
                            v-model="form.is_available"
                            id="is_available"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        >
                            <option :value="true">Yes</option>
                            <option :value="false">No</option>
                        </select>
                        <div v-if="form.errors.is_available" class="text-sm text-red-600 mt-1">{{ form.errors.is_available }}</div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="notes" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Notes</label>
                        <textarea
                            v-model="form.notes"
                            id="notes"
                            rows="3"
                            maxlength="1000"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            placeholder="Add any notes about this booking slot..."
                        ></textarea>
                        <div v-if="form.errors.notes" class="text-sm text-red-600 mt-1">{{ form.errors.notes }}</div>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                    >
                        Update Booking Slot
                    </button>
                    <Link
                        :href="route('admin.companies.staff.booking-slots.index', [company.id, staff.id])"
                        class="text-gray-600 inline-flex items-center hover:text-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:text-gray-400 dark:hover:text-white dark:focus:ring-gray-800"
                    >
                        Cancel
                    </Link>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
