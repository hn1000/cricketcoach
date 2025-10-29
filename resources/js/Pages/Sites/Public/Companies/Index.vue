<script setup>
import PublicLayout from '@/Layouts/Sites/Public/Layout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import SearchFilters from '@/Components/Sites/Public/SearchFilters.vue';
import CoachCard from '@/Components/Sites/Public/CoachCard.vue';
import SearchMap from '@/Components/Sites/Public/SearchMap.vue';

const props = defineProps({
    companies: Object,
    filters: Object,
    userCoordinates: Object,
    filterOptions: Object,
});

const search = ref(props.filters.search || '');
const location = ref(props.filters.location || '');
const viewMode = ref('list'); // 'list' or 'map'
const selectedFilters = ref({
    distance: props.filters.distance || 20,
    specialties: props.filters.specialties || [],
    lesson_types: props.filters.lesson_types || [],
    facilities: props.filters.facilities || [],
    technology: props.filters.technology || [],
});

const performSearch = () => {
    router.get(route('companies.index'), {
        search: search.value,
        location: location.value,
        ...selectedFilters.value,
    }, {
        preserveState: true,
        replace: true,
    });
};

const clearFilters = () => {
    search.value = '';
    location.value = '';
    selectedFilters.value = {
        distance: 20,
        specialties: [],
        lesson_types: [],
        facilities: [],
        technology: [],
    };
    router.get(route('companies.index'));
};

const handleFiltersApply = (filters) => {
    selectedFilters.value = filters;
    performSearch();
};

const hasActiveSearch = computed(() => {
    return search.value || location.value ||
           selectedFilters.value.specialties.length > 0 ||
           selectedFilters.value.lesson_types.length > 0 ||
           selectedFilters.value.facilities.length > 0 ||
           selectedFilters.value.technology.length > 0 ||
           selectedFilters.value.distance !== 20;
});

const handleCompanySelected = (company) => {
    // Scroll to company card or navigate
    router.visit(route('companies.show', company.id));
};
</script>

<template>
    <Head title="Find Cricket Coaches" />

    <PublicLayout>
        <!-- Header Section -->
        <section class="bg-gradient-to-b from-white to-gray-50 dark:from-gray-900 dark:to-gray-800 border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-4">
                    Find Cricket Coaches & Academies
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Discover expert cricket coaches ready to help you improve your game
                </p>

                <!-- Search Bar -->
                <div class="max-w-5xl">
                    <form @submit.prevent="performSearch" class="flex flex-col md:flex-row gap-3">
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
                                    placeholder="Search academies, coaches, specialties..."
                                    class="w-full pl-10 px-4 py-3 text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                />
                            </div>
                        </div>
                        <div class="w-full md:w-72">
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
                                    placeholder="Location or postcode"
                                    class="w-full pl-10 px-4 py-3 text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                />
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button
                                type="submit"
                                class="px-6 py-3 text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg whitespace-nowrap dark:bg-green-600 dark:hover:bg-green-700"
                            >
                                Search
                            </button>
                            <button
                                v-if="hasActiveSearch"
                                type="button"
                                @click="clearFilters"
                                class="px-4 py-3 text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600"
                            >
                                Clear
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <!-- Results Section -->
        <section class="bg-gray-50 dark:bg-gray-900 py-8">
            <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col lg:flex-row gap-6">
                    <!-- Filters Sidebar -->
                    <aside class="w-full lg:w-80 flex-shrink-0">
                        <SearchFilters
                            v-model="selectedFilters"
                            :filter-options="filterOptions"
                            @apply="handleFiltersApply"
                        />
                    </aside>

                    <!-- Results Area -->
                    <div class="flex-1 min-w-0">
                        <!-- Results Header -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 mb-6">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <p class="text-gray-600 dark:text-gray-400">
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ companies.total }}</span>
                                    {{ companies.total === 1 ? 'coach' : 'coaches' }} found
                                    <span v-if="location" class="text-gray-500">
                                        near <span class="font-medium">{{ location }}</span>
                                    </span>
                                </p>

                                <!-- View Toggle -->
                                <div class="flex items-center gap-2">
                                    <button
                                        @click="viewMode = 'list'"
                                        :class="[
                                            'px-4 py-2 rounded-lg font-medium transition-colors',
                                            viewMode === 'list'
                                                ? 'bg-green-600 text-white'
                                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600'
                                        ]"
                                    >
                                        <svg class="w-5 h-5 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        List
                                    </button>
                                    <button
                                        @click="viewMode = 'map'"
                                        :class="[
                                            'px-4 py-2 rounded-lg font-medium transition-colors',
                                            viewMode === 'map'
                                                ? 'bg-green-600 text-white'
                                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600'
                                        ]"
                                    >
                                        <svg class="w-5 h-5 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M12 1.586l-4 4v12.828l4-4V1.586zM3.707 3.293A1 1 0 002 4v10a1 1 0 00.293.707L6 18.414V5.586L3.707 3.293zM17.707 5.293L14 1.586v12.828l2.293 2.293A1 1 0 0018 16V6a1 1 0 00-.293-.707z" clip-rule="evenodd"/>
                                        </svg>
                                        Map
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- List View -->
                        <div v-if="viewMode === 'list'">
                            <!-- Companies Grid -->
                            <div v-if="companies.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                                <CoachCard
                                    v-for="company in companies.data"
                                    :key="company.id"
                                    :company="company"
                                />
                            </div>

                            <!-- No Results -->
                            <div v-else class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-12 text-center">
                                <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white">No coaches found</h3>
                                <p class="mt-2 text-gray-600 dark:text-gray-400">Try adjusting your search criteria or clear filters to see all coaches</p>
                                <button
                                    v-if="hasActiveSearch"
                                    @click="clearFilters"
                                    class="mt-6 px-6 py-3 text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg dark:bg-green-600 dark:hover:bg-green-700"
                                >
                                    View All Coaches
                                </button>
                            </div>

                            <!-- Pagination -->
                            <div v-if="companies.links.length > 3" class="mt-8 flex justify-center">
                                <nav class="relative z-0 inline-flex rounded-lg shadow-sm -space-x-px">
                                    <component
                                        :is="link.url ? Link : 'span'"
                                        v-for="(link, index) in companies.links"
                                        :key="index"
                                        :href="link.url"
                                        :class="[
                                            'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                            link.active
                                                ? 'z-10 bg-green-600 border-green-600 text-white'
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

                        <!-- Map View -->
                        <div v-else class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden" style="height: 600px;">
                            <SearchMap
                                :companies="companies.data"
                                :user-coordinates="userCoordinates"
                                :distance="selectedFilters.distance"
                                @company-selected="handleCompanySelected"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
