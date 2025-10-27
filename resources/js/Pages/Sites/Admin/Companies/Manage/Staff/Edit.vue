<script setup>
import AdminLayout from '@/Layouts/Sites/Admin/Layout.vue';
import { Head, useForm, Link, usePage } from '@inertiajs/vue3';

const props = defineProps({
    company: Object,
    staff: Object,
});

const page = usePage();
const staffMember = page.props.staff.data || page.props.staff;

const form = useForm({
    first_name: staffMember.first_name,
    last_name: staffMember.last_name,
    email: staffMember.email,
    phone: staffMember.phone,
    position: staffMember.position,
    department: staffMember.department,
    hire_date: staffMember.hire_date,
    is_active: staffMember.is_active,
});

const submit = () => {
    form.put(route('admin.companies.staff.update', [props.company.id, staffMember.id]));
};
</script>

<template>
    <Head :title="`Edit Staff - ${company.name}`" />

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
                                <Link :href="route('admin.companies.staff.index', company.id)" class="ml-1 text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-300 dark:hover:text-white">
                                    {{ company.name }}
                                </Link>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-gray-500 md:ml-2 dark:text-gray-400">Edit Staff</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Edit Staff Member</h1>
            </div>
        </div>

        <div class="p-4">
            <form @submit.prevent="submit">
                <div class="grid gap-4 mb-4 sm:grid-cols-2">
                    <div>
                        <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">First Name *</label>
                        <input
                            v-model="form.first_name"
                            type="text"
                            id="first_name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            required
                        >
                        <div v-if="form.errors.first_name" class="text-sm text-red-600 mt-1">{{ form.errors.first_name }}</div>
                    </div>

                    <div>
                        <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Last Name *</label>
                        <input
                            v-model="form.last_name"
                            type="text"
                            id="last_name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            required
                        >
                        <div v-if="form.errors.last_name" class="text-sm text-red-600 mt-1">{{ form.errors.last_name }}</div>
                    </div>

                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                        <input
                            v-model="form.email"
                            type="email"
                            id="email"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        >
                        <div v-if="form.errors.email" class="text-sm text-red-600 mt-1">{{ form.errors.email }}</div>
                    </div>

                    <div>
                        <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone</label>
                        <input
                            v-model="form.phone"
                            type="text"
                            id="phone"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        >
                        <div v-if="form.errors.phone" class="text-sm text-red-600 mt-1">{{ form.errors.phone }}</div>
                    </div>

                    <div>
                        <label for="position" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Position</label>
                        <input
                            v-model="form.position"
                            type="text"
                            id="position"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            placeholder="e.g., Head Coach, Assistant"
                        >
                        <div v-if="form.errors.position" class="text-sm text-red-600 mt-1">{{ form.errors.position }}</div>
                    </div>

                    <div>
                        <label for="department" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Department</label>
                        <input
                            v-model="form.department"
                            type="text"
                            id="department"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            placeholder="e.g., Coaching, Administration"
                        >
                        <div v-if="form.errors.department" class="text-sm text-red-600 mt-1">{{ form.errors.department }}</div>
                    </div>

                    <div>
                        <label for="hire_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Hire Date</label>
                        <input
                            v-model="form.hire_date"
                            type="date"
                            id="hire_date"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        >
                        <div v-if="form.errors.hire_date" class="text-sm text-red-600 mt-1">{{ form.errors.hire_date }}</div>
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
                </div>

                <div class="flex items-center space-x-4">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                    >
                        Update Staff Member
                    </button>
                    <Link
                        :href="route('admin.companies.staff.index', company.id)"
                        class="text-gray-600 inline-flex items-center hover:text-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:text-gray-400 dark:hover:text-white dark:focus:ring-gray-800"
                    >
                        Cancel
                    </Link>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
