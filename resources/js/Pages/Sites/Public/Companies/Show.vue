<script setup>
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    company: Object,
});
</script>

<template>
    <Head :title="company.name" />

    <div class="min-h-screen bg-gray-50">
        <!-- Header with back button -->
        <div class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <Link :href="route('companies.index')" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to companies
                </Link>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Company Header -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
                <div class="h-64 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                    <div v-if="company.logo" class="w-full h-full">
                        <img :src="company.logo" :alt="company.name" class="w-full h-full object-cover" />
                    </div>
                    <div v-else class="text-white text-8xl font-bold">
                        {{ company.name.charAt(0).toUpperCase() }}
                    </div>
                </div>

                <div class="p-8">
                    <h1 class="text-4xl font-bold text-gray-900">{{ company.name }}</h1>

                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div v-if="company.email" class="flex items-start">
                            <svg class="w-5 h-5 mr-3 mt-0.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                            <a :href="`mailto:${company.email}`" class="text-blue-600 hover:underline">
                                {{ company.email }}
                            </a>
                        </div>

                        <div v-if="company.phone" class="flex items-start">
                            <svg class="w-5 h-5 mr-3 mt-0.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                            </svg>
                            <a :href="`tel:${company.phone}`" class="text-blue-600 hover:underline">
                                {{ company.phone }}
                            </a>
                        </div>

                        <div v-if="company.address" class="flex items-start">
                            <svg class="w-5 h-5 mr-3 mt-0.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-gray-700">{{ company.address }}</span>
                        </div>

                        <div v-if="company.website" class="flex items-start">
                            <svg class="w-5 h-5 mr-3 mt-0.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd"/>
                            </svg>
                            <a :href="company.website" target="_blank" class="text-blue-600 hover:underline">
                                Visit website
                            </a>
                        </div>
                    </div>

                    <p v-if="company.description" class="mt-6 text-gray-700 whitespace-pre-line">
                        {{ company.description }}
                    </p>
                </div>
            </div>

            <!-- Staff Members -->
            <div class="bg-white rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Our Team</h2>

                <div v-if="company.staff && company.staff.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div
                        v-for="staff in company.staff"
                        :key="staff.id"
                        class="border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow"
                    >
                        <div class="flex items-center mb-4">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white text-2xl font-bold">
                                {{ staff.first_name.charAt(0) }}{{ staff.last_name.charAt(0) }}
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ staff.first_name }} {{ staff.last_name }}
                                </h3>
                                <p v-if="staff.position" class="text-sm text-gray-600">{{ staff.position }}</p>
                            </div>
                        </div>

                        <p v-if="staff.department" class="text-sm text-gray-600 mb-4">
                            <span class="font-medium">Department:</span> {{ staff.department }}
                        </p>

                        <Link
                            :href="route('booking.create', { company: company.id, staff: staff.id })"
                            class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                        >
                            Book Appointment
                        </Link>
                    </div>
                </div>

                <div v-else class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No staff members available</h3>
                    <p class="mt-1 text-sm text-gray-500">Check back later for available appointments</p>
                </div>
            </div>
        </div>
    </div>
</template>
