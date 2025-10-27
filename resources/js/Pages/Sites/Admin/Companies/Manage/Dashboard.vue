<script setup>
import AdminLayout from '@/Layouts/Sites/Admin/Layout.vue';
import { Head, Link } from '@inertiajs/vue3';
import Tabs from './Tabs.vue';

const props = defineProps({
    company: Object,
    stats: {
        type: Object,
        default: () => ({})
    }
});
</script>

<template>
    <Head :title="`${company.name} - Dashboard`" />

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
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-gray-500 md:ml-2 dark:text-gray-400">{{ company.name }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">{{ company.name }}</h1>
            </div>
        </div>

        <!-- Tabs -->
        <div class="bg-white dark:bg-gray-800">
            <Tabs :company="company" active-tab="dashboard" />
        </div>

        <!-- Dashboard Content -->
        <div class="p-4 bg-white dark:bg-gray-800">
            <!-- Company Overview -->
            <div class="grid gap-4 mb-4 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Company Details Card -->
                <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                    <h3 class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">Company Details</h3>
                    <dl class="space-y-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                            <dd class="mt-1">
                                <span
                                    v-if="company.is_active"
                                    class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300"
                                >
                                    Active
                                </span>
                                <span
                                    v-else
                                    class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300"
                                >
                                    Inactive
                                </span>
                            </dd>
                        </div>
                        <div v-if="company.email">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ company.email }}</dd>
                        </div>
                        <div v-if="company.phone">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ company.phone }}</dd>
                        </div>
                        <div v-if="company.website">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Website</dt>
                            <dd class="mt-1 text-sm text-blue-600 dark:text-blue-500">
                                <a :href="company.website" target="_blank" class="hover:underline">
                                    {{ company.website }}
                                </a>
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Staff Stats Card -->
                <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                    <h3 class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">Staff Overview</h3>
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.total_staff || 0 }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Total Staff</div>
                        </div>
                        <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg dark:bg-blue-900">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Active: {{ stats.active_staff || 0 }}</span>
                        <span class="text-gray-500 dark:text-gray-400">Inactive: {{ stats.inactive_staff || 0 }}</span>
                    </div>
                    <Link
                        :href="route('admin.companies.staff.index', company.id)"
                        class="inline-flex items-center mt-3 text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-500 dark:hover:text-blue-400"
                    >
                        View all staff
                        <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </Link>
                </div>

                <!-- Quick Actions Card -->
                <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                    <h3 class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">Quick Actions</h3>
                    <div class="space-y-2">
                        <Link
                            :href="route('admin.companies.edit', company.id)"
                            class="flex items-center p-2 text-sm text-gray-900 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                        >
                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path>
                                <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path>
                            </svg>
                            Edit Company Details
                        </Link>
                        <Link
                            :href="route('admin.companies.staff.create', company.id)"
                            class="flex items-center p-2 text-sm text-gray-900 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                        >
                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                            </svg>
                            Add New Staff Member
                        </Link>
                        <Link
                            :href="route('admin.companies.staff.index', company.id)"
                            class="flex items-center p-2 text-sm text-gray-900 rounded-lg hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                        >
                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                            </svg>
                            Manage Staff
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Company Description -->
            <div v-if="company.description" class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <h3 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">About</h3>
                <p class="text-sm text-gray-700 dark:text-gray-300">{{ company.description }}</p>
            </div>

            <!-- Company Address -->
            <div v-if="company.address" class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <h3 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">Address</h3>
                <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ company.address }}</p>
            </div>
        </div>
    </AdminLayout>
</template>
