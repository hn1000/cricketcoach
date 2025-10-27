<script setup>
import PublicLayout from '@/Layouts/Sites/Public/Layout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    company: Object,
});
</script>

<template>
    <Head :title="company.name" />

    <PublicLayout>
        <!-- Breadcrumb -->
        <section class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <Link :href="route('home')" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                                </svg>
                                Home
                            </Link>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <Link :href="route('companies.index')" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">
                                    Academies
                                </Link>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">{{ company.name }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
        </section>

        <!-- Company Header -->
        <section class="bg-gradient-to-b from-white to-gray-50 dark:from-gray-900 dark:to-gray-800">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-lg overflow-hidden">
                    <!-- Logo/Banner -->
                    <div class="h-48 md:h-64 bg-gradient-to-br from-blue-500 to-blue-700 dark:from-blue-600 dark:to-blue-900 flex items-center justify-center p-8">
                        <div v-if="company.logo" class="w-full h-full flex items-center justify-center">
                            <img :src="company.logo" :alt="company.name" class="h-full object-contain" />
                        </div>
                        <div v-else class="text-white text-7xl md:text-8xl font-bold">
                            {{ company.name.charAt(0).toUpperCase() }}
                        </div>
                    </div>

                    <!-- Company Info -->
                    <div class="p-6 md:p-8">
                        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white mb-6">
                            {{ company.name }}
                        </h1>

                        <p v-if="company.description" class="text-gray-600 dark:text-gray-400 text-lg mb-6 whitespace-pre-line">
                            {{ company.description }}
                        </p>

                        <!-- Contact Info Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <div v-if="company.email" class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Email</p>
                                    <a :href="`mailto:${company.email}`" class="text-blue-600 hover:underline dark:text-blue-500">
                                        {{ company.email }}
                                    </a>
                                </div>
                            </div>

                            <div v-if="company.phone" class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Phone</p>
                                    <a :href="`tel:${company.phone}`" class="text-blue-600 hover:underline dark:text-blue-500">
                                        {{ company.phone }}
                                    </a>
                                </div>
                            </div>

                            <div v-if="company.address" class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Address</p>
                                    <p class="text-gray-600 dark:text-gray-400">{{ company.address }}</p>
                                </div>
                            </div>

                            <div v-if="company.website" class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Website</p>
                                    <a :href="company.website" target="_blank" class="text-blue-600 hover:underline dark:text-blue-500 inline-flex items-center">
                                        Visit website
                                        <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"/>
                                            <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Coaches Section -->
        <section class="bg-gray-50 dark:bg-gray-900 py-12">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-8">
                    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-2">
                        Our Coaches
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400">
                        Meet our expert coaching team and book your session
                    </p>
                </div>

                <div v-if="company.staff && company.staff.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div
                        v-for="staff in company.staff"
                        :key="staff.id"
                        class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm hover:shadow-lg transition-all p-6"
                    >
                        <!-- Coach Avatar & Info -->
                        <div class="flex items-start mb-4">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white text-xl font-bold flex-shrink-0">
                                {{ staff.first_name.charAt(0) }}{{ staff.last_name.charAt(0) }}
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                    {{ staff.first_name }} {{ staff.last_name }}
                                </h3>
                                <p v-if="staff.position" class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ staff.position }}
                                </p>
                            </div>
                        </div>

                        <div v-if="staff.department" class="mb-4 px-3 py-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <p class="text-sm text-blue-700 dark:text-blue-400">
                                <span class="font-medium">Specialty:</span> {{ staff.department }}
                            </p>
                        </div>

                        <!-- Book Button -->
                        <Link
                            :href="route('booking.create', { company: company.id, staff: staff.id })"
                            class="block w-full text-center px-4 py-3 bg-blue-700 hover:bg-blue-800 text-white font-medium rounded-lg transition-colors focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        >
                            Book Coaching Session
                        </Link>
                    </div>
                </div>

                <!-- No Coaches -->
                <div v-else class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white">No Coaches Available</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">This academy doesn't have any coaches listed yet. Please check back later or contact them directly.</p>
                    <div class="mt-6">
                        <Link :href="route('companies.index')" class="inline-flex items-center px-6 py-3 text-base font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Browse Other Academies
                        </Link>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
