<script setup>
import PublicLayout from '@/Layouts/Sites/Public/Layout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    companies: Object,
    filters: Object,
});

const search = ref(props.filters.search || '');
const location = ref(props.filters.location || '');

const performSearch = () => {
    router.get(route('companies.index'), {
        search: search.value,
        location: location.value
    }, {
        preserveState: true,
        replace: true,
    });
};

const clearFilters = () => {
    search.value = '';
    location.value = '';
    router.get(route('companies.index'));
};
</script>

<template>
    <Head title="Browse Cricket Academies" />

    <PublicLayout>
        <!-- Header Section -->
        <section class="bg-gradient-to-b from-white to-gray-50 dark:from-gray-900 dark:to-gray-800 border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-4">
                    Browse Cricket Academies
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400">
                    Discover expert cricket coaches and academies ready to help you improve your game
                </p>

                <!-- Search & Filter Bar -->
                <div class="mt-8 max-w-4xl">
                    <form @submit.prevent="performSearch" class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <label for="search" class="sr-only">Search</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input
                                    id="search"
                                    v-model="search"
                                    type="text"
                                    placeholder="Search academies, specialties, coaches..."
                                    class="w-full pl-10 px-4 py-3 text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-4 focus:ring-blue-300 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                />
                            </div>
                        </div>
                        <div class="w-full md:w-64">
                            <label for="location" class="sr-only">Location</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <input
                                    id="location"
                                    v-model="location"
                                    type="text"
                                    placeholder="Location"
                                    class="w-full pl-10 px-4 py-3 text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-4 focus:ring-blue-300 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                />
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button
                                type="submit"
                                class="px-6 py-3 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                            >
                                Search
                            </button>
                            <button
                                v-if="search || location"
                                type="button"
                                @click="clearFilters"
                                class="px-4 py-3 text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600 dark:focus:ring-gray-700"
                            >
                                Clear
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <!-- Results Section -->
        <section class="bg-gray-50 dark:bg-gray-900 py-12">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Results Count -->
                <div class="mb-6 flex items-center justify-between">
                    <p class="text-gray-600 dark:text-gray-400">
                        <span class="font-semibold text-gray-900 dark:text-white">{{ companies.total }}</span>
                        {{ companies.total === 1 ? 'academy' : 'academies' }} found
                    </p>
                </div>

                <!-- Companies Grid -->
                <div v-if="companies.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <Link
                        v-for="company in companies.data"
                        :key="company.id"
                        :href="route('companies.show', company.id)"
                        class="block bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-lg transition-all dark:bg-gray-800 dark:border-gray-700 overflow-hidden group"
                    >
                        <!-- Company Logo/Header -->
                        <div class="h-40 bg-gradient-to-br from-blue-500 to-blue-700 dark:from-blue-600 dark:to-blue-900 flex items-center justify-center p-6">
                            <div v-if="company.logo" class="w-full h-full flex items-center justify-center">
                                <img :src="company.logo" :alt="company.name" class="h-full object-contain" />
                            </div>
                            <div v-else class="text-white text-5xl font-bold">
                                {{ company.name.charAt(0).toUpperCase() }}
                            </div>
                        </div>

                        <!-- Company Info -->
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-500 transition-colors mb-2">
                                {{ company.name }}
                            </h3>
                            <p v-if="company.description" class="text-gray-600 dark:text-gray-400 line-clamp-3 text-sm mb-4">
                                {{ company.description }}
                            </p>

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

                            <!-- CTA -->
                            <div class="flex items-center text-blue-600 dark:text-blue-500 font-medium">
                                View Details & Book
                                <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                    </Link>
                </div>

                <!-- No Results -->
                <div v-else class="text-center py-16">
                    <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white">No academies found</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Try adjusting your search criteria or clear filters to see all academies</p>
                    <button
                        v-if="search || location"
                        @click="clearFilters"
                        class="mt-6 px-6 py-3 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                    >
                        View All Academies
                    </button>
                </div>

                <!-- Pagination -->
                <div v-if="companies.links.length > 3" class="mt-12 flex justify-center">
                    <nav class="relative z-0 inline-flex rounded-lg shadow-sm -space-x-px">
                        <component
                            :is="link.url ? Link : 'span'"
                            v-for="(link, index) in companies.links"
                            :key="index"
                            :href="link.url"
                            :class="[
                                'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                link.active
                                    ? 'z-10 bg-blue-600 border-blue-600 text-white'
                                    : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700',
                                !link.url && 'cursor-not-allowed opacity-50',
                                index === 0 && 'rounded-l-lg',
                                index === companies.links.length - 1 && 'rounded-r-lg',
                            ]"
                            v-html="link.label"
                        />
                    </nav>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
