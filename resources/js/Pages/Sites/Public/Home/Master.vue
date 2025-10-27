<script setup>
import PublicLayout from '@/Layouts/Sites/Public/Layout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Home" />

    <PublicLayout>
        <!-- Hero Section -->
        <section class="bg-white dark:bg-gray-900">
            <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16 lg:px-12">
                <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white">
                    Company Directory
                </h1>
                <p class="mb-8 text-lg font-normal text-gray-500 lg:text-xl sm:px-16 xl:px-48 dark:text-gray-400">
                    Discover companies and connect with businesses in our directory
                </p>
            </div>
        </section>

        <!-- Companies Grid -->
        <section class="bg-gray-50 dark:bg-gray-900 py-8 px-4 mx-auto max-w-screen-xl lg:px-6">
            <div v-if="$page.props.companies.data.length === 0" class="text-center py-12">
                <p class="text-gray-500 dark:text-gray-400">No companies found.</p>
            </div>

            <div v-else class="grid gap-8 lg:grid-cols-3 md:grid-cols-2">
                <article
                    v-for="company in $page.props.companies.data"
                    :key="company.id"
                    class="p-6 bg-white rounded-lg border border-gray-200 shadow-md dark:bg-gray-800 dark:border-gray-700"
                >
                    <div v-if="company.logo" class="mb-4">
                        <img :src="company.logo" :alt="company.name" class="h-16 object-contain">
                    </div>
                    <h2 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        {{ company.name }}
                    </h2>
                    <p v-if="company.description" class="mb-5 font-light text-gray-500 dark:text-gray-400">
                        {{ company.description }}
                    </p>
                    <div class="flex flex-col space-y-2">
                        <a
                            v-if="company.website"
                            :href="company.website"
                            target="_blank"
                            class="inline-flex items-center font-medium text-primary-600 dark:text-primary-500 hover:underline"
                        >
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"></path>
                                <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"></path>
                            </svg>
                            Visit Website
                        </a>
                        <span v-if="company.email" class="text-sm text-gray-500 dark:text-gray-400">
                            <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                            </svg>
                            {{ company.email }}
                        </span>
                        <span v-if="company.phone" class="text-sm text-gray-500 dark:text-gray-400">
                            <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                            </svg>
                            {{ company.phone }}
                        </span>
                        <span v-if="company.address" class="text-sm text-gray-500 dark:text-gray-400">
                            <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                            {{ company.address }}
                        </span>
                    </div>
                </article>
            </div>
        </section>
    </PublicLayout>
</template>
