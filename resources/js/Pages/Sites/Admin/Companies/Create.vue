<script setup>
import AdminLayout from '@/Layouts/Sites/Admin/Layout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    description: '',
    logo: '',
    website: '',
    email: '',
    phone: '',
    address: '',
    is_active: true,
});

const submit = () => {
    form.post(route('admin.companies.store'));
};
</script>

<template>
    <Head title="Create Company" />

    <AdminLayout>
        <div class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <div class="w-full mb-1">
                <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Create Company</h1>
            </div>
        </div>

        <div class="p-4">
            <form @submit.prevent="submit">
                <div class="grid gap-4 mb-4 sm:grid-cols-2">
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Company Name *</label>
                        <input
                            v-model="form.name"
                            type="text"
                            id="name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            required
                        >
                        <div v-if="form.errors.name" class="text-sm text-red-600 mt-1">{{ form.errors.name }}</div>
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
                        <label for="website" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Website</label>
                        <input
                            v-model="form.website"
                            type="url"
                            id="website"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            placeholder="https://example.com"
                        >
                        <div v-if="form.errors.website" class="text-sm text-red-600 mt-1">{{ form.errors.website }}</div>
                    </div>

                    <div>
                        <label for="logo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Logo URL</label>
                        <input
                            v-model="form.logo"
                            type="url"
                            id="logo"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                            placeholder="https://example.com/logo.png"
                        >
                        <div v-if="form.errors.logo" class="text-sm text-red-600 mt-1">{{ form.errors.logo }}</div>
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
                        <label for="address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Address</label>
                        <textarea
                            v-model="form.address"
                            id="address"
                            rows="3"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        ></textarea>
                        <div v-if="form.errors.address" class="text-sm text-red-600 mt-1">{{ form.errors.address }}</div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                        <textarea
                            v-model="form.description"
                            id="description"
                            rows="4"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        ></textarea>
                        <div v-if="form.errors.description" class="text-sm text-red-600 mt-1">{{ form.errors.description }}</div>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                    >
                        Create Company
                    </button>
                    <a
                        :href="route('admin.companies.index')"
                        class="text-gray-600 inline-flex items-center hover:text-gray-900 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:text-gray-400 dark:hover:text-white dark:focus:ring-gray-800"
                    >
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
