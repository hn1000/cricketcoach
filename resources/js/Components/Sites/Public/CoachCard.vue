<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    company: {
        type: Object,
        required: true,
    },
});

const formatLabel = (value) => {
    return value.split('-').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
};
</script>

<template>
    <Link
        :href="route('companies.show', company.id)"
        class="block bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-lg transition-all dark:bg-gray-800 dark:border-gray-700 overflow-hidden group"
    >
        <!-- Company Logo/Header -->
        <div class="h-40 bg-gradient-to-br from-green-500 to-green-700 dark:from-green-600 dark:to-green-900 flex items-center justify-center p-6 relative">
            <div v-if="company.logo" class="w-full h-full flex items-center justify-center">
                <img :src="company.logo" :alt="company.name" class="h-full object-contain" />
            </div>
            <div v-else class="text-white text-5xl font-bold">
                {{ company.name.charAt(0).toUpperCase() }}
            </div>

            <!-- Distance Badge -->
            <div
                v-if="company.distance !== undefined"
                class="absolute top-3 right-3 bg-white dark:bg-gray-800 rounded-full px-3 py-1 shadow-md"
            >
                <span class="text-sm font-semibold text-gray-900 dark:text-white">
                    {{ company.distance }} mi
                </span>
            </div>
        </div>

        <!-- Company Info -->
        <div class="p-6">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white group-hover:text-green-600 dark:group-hover:text-green-500 transition-colors mb-2">
                {{ company.name }}
            </h3>

            <p v-if="company.description" class="text-gray-600 dark:text-gray-400 line-clamp-2 text-sm mb-4">
                {{ company.description }}
            </p>

            <!-- Coach Count -->
            <div v-if="company.staff && company.staff.length > 0" class="flex items-center text-sm text-gray-600 dark:text-gray-400 mb-4">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                </svg>
                <span>{{ company.staff.length }} {{ company.staff.length === 1 ? 'coach' : 'coaches' }} available</span>
            </div>

            <!-- Company Details -->
            <div class="space-y-2 mb-4">
                <div v-if="company.address" class="flex items-start text-sm text-gray-500 dark:text-gray-400">
                    <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="line-clamp-2">{{ company.address }}</span>
                </div>
                <div v-if="company.phone" class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                    </svg>
                    {{ company.phone }}
                </div>
            </div>

            <!-- Specialties Tags -->
            <div v-if="company.staff && company.staff.length > 0" class="mb-4">
                <div class="flex flex-wrap gap-1">
                    <template v-for="coach in company.staff" :key="coach.id">
                        <span
                            v-for="specialty in (coach.specialties || []).slice(0, 3)"
                            :key="specialty"
                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-300"
                        >
                            {{ formatLabel(specialty) }}
                        </span>
                    </template>
                </div>
            </div>

            <!-- CTA -->
            <div class="flex items-center text-green-600 dark:text-green-500 font-medium">
                View Details & Book
                <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </div>
    </Link>
</template>
