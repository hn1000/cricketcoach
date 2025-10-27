<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    companies: Object,
    filters: Object,
});

const search = ref(props.filters.search || '');

const performSearch = () => {
    router.get(route('companies.index'), { search: search.value }, {
        preserveState: true,
        replace: true,
    });
};
</script>

<template>
    <Head title="Browse Companies" />

    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <h1 class="text-3xl font-bold text-gray-900">Find a Service Provider</h1>
                <p class="mt-2 text-gray-600">Browse our trusted service providers and book your appointment</p>
            </div>
        </header>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Search Bar -->
            <div class="mb-8">
                <div class="relative">
                    <input
                        v-model="search"
                        @keyup.enter="performSearch"
                        type="text"
                        placeholder="Search companies..."
                        class="w-full px-4 py-3 pl-12 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                    <svg class="absolute left-4 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <button
                        @click="performSearch"
                        class="absolute right-2 top-2 px-4 py-1.5 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                    >
                        Search
                    </button>
                </div>
            </div>

            <!-- Companies Grid -->
            <div v-if="companies.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <Link
                    v-for="company in companies.data"
                    :key="company.id"
                    :href="route('companies.show', company.id)"
                    class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow overflow-hidden group"
                >
                    <!-- Company Logo/Image -->
                    <div class="h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                        <div v-if="company.logo" class="w-full h-full">
                            <img :src="company.logo" :alt="company.name" class="w-full h-full object-cover" />
                        </div>
                        <div v-else class="text-white text-6xl font-bold">
                            {{ company.name.charAt(0).toUpperCase() }}
                        </div>
                    </div>

                    <!-- Company Info -->
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">
                            {{ company.name }}
                        </h3>
                        <p v-if="company.description" class="mt-2 text-gray-600 line-clamp-2">
                            {{ company.description }}
                        </p>
                        <div class="mt-4 flex items-center text-sm text-gray-500">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            {{ company.address || 'Location not specified' }}
                        </div>
                        <div class="mt-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                Book Now
                            </span>
                        </div>
                    </div>
                </Link>
            </div>

            <!-- No Results -->
            <div v-else class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No companies found</h3>
                <p class="mt-1 text-sm text-gray-500">Try adjusting your search criteria</p>
            </div>

            <!-- Pagination -->
            <div v-if="companies.links.length > 3" class="mt-8 flex justify-center">
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                    <component
                        :is="link.url ? 'Link' : 'span'"
                        v-for="(link, index) in companies.links"
                        :key="index"
                        :href="link.url"
                        :class="[
                            'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                            link.active
                                ? 'z-10 bg-blue-600 border-blue-600 text-white'
                                : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50',
                            !link.url && 'cursor-not-allowed opacity-50',
                            index === 0 && 'rounded-l-md',
                            index === companies.links.length - 1 && 'rounded-r-md',
                        ]"
                        v-html="link.label"
                    />
                </nav>
            </div>
        </div>
    </div>
</template>
