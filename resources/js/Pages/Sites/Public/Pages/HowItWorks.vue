<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/Sites/Public/Layout.vue';
</script>

<template>
    <Head title="How It Works" />

    <PublicLayout>
        <!-- Hero Section -->
        <section class="bg-white dark:bg-gray-900">
            <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16 lg:px-12">
                <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white">
                    How It Works
                </h1>
                <p class="mb-8 text-lg font-normal text-gray-500 lg:text-xl sm:px-16 xl:px-48 dark:text-gray-400">
                    Book a coaching session in three simple steps
                </p>
            </div>
        </section>

        <!-- Steps Section -->
        <section class="bg-gray-50 dark:bg-gray-800">
            <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
                <ol class="relative border-l border-gray-200 dark:border-gray-700 max-w-4xl mx-auto">
                    <!-- Step 1 -->
                    <li class="mb-10 ml-6">
                        <span class="absolute flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full -left-6 ring-8 ring-white dark:ring-gray-800 dark:bg-blue-900">
                            <svg class="w-6 h-6 text-blue-800 dark:text-blue-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        <h3 class="flex items-center mb-1 text-2xl font-semibold text-gray-900 dark:text-white">
                            Find Your Coach
                        </h3>
                        <p class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">
                            Browse our directory of expert cricket coaches and academies. Filter by location, specialization, or availability to find the perfect match for your needs.
                        </p>
                    </li>

                    <!-- Step 2 -->
                    <li class="mb-10 ml-6">
                        <span class="absolute flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full -left-6 ring-8 ring-white dark:ring-gray-800 dark:bg-blue-900">
                            <svg class="w-6 h-6 text-blue-800 dark:text-blue-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        <h3 class="mb-1 text-2xl font-semibold text-gray-900 dark:text-white">
                            Book Your Session
                        </h3>
                        <p class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">
                            Select a convenient date and time from the coach's availability calendar. Fill in your details and proceed to secure payment. You'll receive instant confirmation via email.
                        </p>
                    </li>

                    <!-- Step 3 -->
                    <li class="ml-6">
                        <span class="absolute flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full -left-6 ring-8 ring-white dark:ring-gray-800 dark:bg-blue-900">
                            <svg class="w-6 h-6 text-blue-800 dark:text-blue-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        <h3 class="mb-1 text-2xl font-semibold text-gray-900 dark:text-white">
                            Attend & Improve
                        </h3>
                        <p class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">
                            Show up to your session ready to learn and improve. Track your progress through your dashboard and book follow-up sessions as needed. You can cancel or reschedule up to 24 hours in advance.
                        </p>
                    </li>
                </ol>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="bg-white dark:bg-gray-900">
            <div class="py-8 px-4 mx-auto max-w-screen-xl sm:py-16 lg:px-6">
                <div class="mx-auto max-w-screen-sm text-center">
                    <h2 class="mb-4 text-3xl tracking-tight font-bold leading-tight text-gray-900 dark:text-white">
                        Start Your Cricket Journey
                    </h2>
                    <p class="mb-6 font-light text-gray-500 dark:text-gray-400 md:text-lg">
                        Join thousands of players improving their game with expert coaching
                    </p>
                    <Link
                        :href="route('companies.index')"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                    >
                        Find a Coach
                    </Link>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
