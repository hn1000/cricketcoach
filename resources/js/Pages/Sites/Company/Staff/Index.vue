<script setup>
import { Head, Link } from '@inertiajs/vue3';
import CompanyLayout from '@/Layouts/Sites/Company/Layout.vue';

const props = defineProps({
    staff: Object,
});
</script>

<template>
    <Head title="Manage Coaches" />

    <CompanyLayout>
        <div class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700 mb-4 rounded-lg">
            <div class="w-full mb-1">
                <div class="mb-4">
                    <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Manage Coaches</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Add, edit, and manage your coaching staff
                    </p>
                </div>
                <div class="flex items-center">
                    <Link
                        :href="route('company.staff.create')"
                        class="inline-flex items-center px-4 py-2 bg-green-700 hover:bg-green-800 text-white text-sm font-medium rounded-lg focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700"
                    >
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                        </svg>
                        Add Coach
                    </Link>
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
                                        Name
                                    </th>
                                    <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Contact
                                    </th>
                                    <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Position
                                    </th>
                                    <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Company
                                    </th>
                                    <th scope="col" class="p-4 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                <tr v-if="staff.data.length === 0">
                                    <td colspan="5" class="p-4 text-center text-gray-500 dark:text-gray-400">
                                        <div class="py-12">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                            <p class="mt-4 text-lg font-medium">No coaches yet</p>
                                            <p class="mt-2 text-sm">Get started by adding your first coach</p>
                                            <div class="mt-6">
                                                <Link
                                                    :href="route('company.staff.create')"
                                                    class="inline-flex items-center px-4 py-2 bg-green-700 hover:bg-green-800 text-white text-sm font-medium rounded-lg"
                                                >
                                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Add Your First Coach
                                                </Link>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr
                                    v-for="member in staff.data"
                                    :key="member.id"
                                    class="hover:bg-gray-50 dark:hover:bg-gray-700"
                                >
                                    <td class="p-4 text-base font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-semibold mr-3">
                                                {{ member.first_name.charAt(0) }}{{ member.last_name.charAt(0) }}
                                            </div>
                                            <div>
                                                <div class="font-semibold">{{ member.first_name }} {{ member.last_name }}</div>
                                                <div v-if="member.department" class="text-xs text-gray-500 dark:text-gray-400">{{ member.department }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4 text-sm font-normal text-gray-500 dark:text-gray-400">
                                        <div v-if="member.email" class="mb-1">
                                            <a :href="`mailto:${member.email}`" class="text-green-600 hover:underline dark:text-green-400">
                                                {{ member.email }}
                                            </a>
                                        </div>
                                        <div v-if="member.phone" class="text-xs">
                                            <a :href="`tel:${member.phone}`" class="text-gray-600 hover:underline dark:text-gray-400">
                                                {{ member.phone }}
                                            </a>
                                        </div>
                                    </td>
                                    <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">
                                        {{ member.position || '-' }}
                                    </td>
                                    <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">
                                        {{ member.company.name }}
                                    </td>
                                    <td class="p-4 space-x-2 whitespace-nowrap">
                                        <Link
                                            :href="route('company.staff.edit', member.id)"
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700"
                                        >
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path>
                                                <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path>
                                            </svg>
                                            Edit
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
        <div v-if="staff.links.length > 3" class="sticky bottom-0 right-0 items-center w-full p-4 bg-white border-t border-gray-200 sm:flex sm:justify-between dark:bg-gray-800 dark:border-gray-700 mt-4 rounded-lg">
            <div class="flex items-center mb-4 sm:mb-0">
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                    Showing
                    <span class="font-semibold text-gray-900 dark:text-white">{{ staff.from }}-{{ staff.to }}</span>
                    of
                    <span class="font-semibold text-gray-900 dark:text-white">{{ staff.total }}</span>
                </span>
            </div>
            <div class="flex items-center space-x-3">
                <Link
                    v-for="(link, index) in staff.links"
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
