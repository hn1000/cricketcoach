<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/Sites/Public/Layout.vue';
</script>

<template>
    <Head title="About Us" />

    <PublicLayout>
        <!-- Hero Section -->
        <section class="bg-white dark:bg-gray-900">
            <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
                <div class="mx-auto max-w-screen-md text-center">
                    <h1 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">About Cricket Coach</h1>
                    <p class="mb-8 font-light text-gray-500 sm:text-xl dark:text-gray-400">
                        Connecting aspiring cricketers with expert coaches and professional academies to help you reach your full potential.
                    </p>
                </div>
            </div>
        </section>

        <!-- Mission Section -->
        <section class="bg-gray-50 dark:bg-gray-800">
            <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
                <div class="max-w-screen-lg mx-auto">
                    <h2 class="mb-4 text-3xl tracking-tight font-bold text-gray-900 dark:text-white">Our Mission</h2>
                    <p class="mb-4 text-gray-500 dark:text-gray-400">
                        At Cricket Coach, we believe that every player deserves access to quality coaching. Whether you're just starting out or looking to refine your skills, our platform makes it easy to find, book, and connect with experienced cricket coaches and renowned academies.
                    </p>
                    <p class="mb-4 text-gray-500 dark:text-gray-400">
                        We're committed to making cricket coaching accessible, affordable, and efficient for everyone who loves the game.
                    </p>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="bg-white dark:bg-gray-900">
            <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
                <div class="mx-auto max-w-screen-md text-center mb-8 lg:mb-12">
                    <h2 class="mb-4 text-3xl tracking-tight font-bold text-gray-900 dark:text-white">Why Choose Us</h2>
                    <p class="mb-5 font-light text-gray-500 sm:text-xl dark:text-gray-400">
                        Everything you need to find and book the perfect coaching session
                    </p>
                </div>

                <div class="space-y-8 lg:grid lg:grid-cols-3 sm:gap-6 xl:gap-10 lg:space-y-0">
                    <!-- Feature 1 -->
                    <div class="flex flex-col p-6 mx-auto max-w-lg text-center text-gray-900 bg-white rounded-lg border border-gray-100 shadow dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                        <div class="flex justify-center items-center mb-4">
                            <svg class="w-12 h-12 text-blue-600 dark:text-blue-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                            </svg>
                        </div>
                        <h3 class="mb-2 text-xl font-semibold">Expert Coaches</h3>
                        <p class="font-light text-gray-500 dark:text-gray-400">
                            Access a network of verified, experienced cricket coaches with proven track records
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="flex flex-col p-6 mx-auto max-w-lg text-center text-gray-900 bg-white rounded-lg border border-gray-100 shadow dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                        <div class="flex justify-center items-center mb-4">
                            <svg class="w-12 h-12 text-blue-600 dark:text-blue-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <h3 class="mb-2 text-xl font-semibold">Easy Booking</h3>
                        <p class="font-light text-gray-500 dark:text-gray-400">
                            Simple, streamlined booking process with instant confirmation and calendar management
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="flex flex-col p-6 mx-auto max-w-lg text-center text-gray-900 bg-white rounded-lg border border-gray-100 shadow dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                        <div class="flex justify-center items-center mb-4">
                            <svg class="w-12 h-12 text-blue-600 dark:text-blue-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <h3 class="mb-2 text-xl font-semibold">Secure Payments</h3>
                        <p class="font-light text-gray-500 dark:text-gray-400">
                            Safe, secure payment processing with multiple payment options for your convenience
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="bg-gray-50 dark:bg-gray-800">
            <div class="py-8 px-4 mx-auto max-w-screen-xl sm:py-16 lg:px-6">
                <div class="mx-auto max-w-screen-sm text-center">
                    <h2 class="mb-4 text-3xl tracking-tight font-bold leading-tight text-gray-900 dark:text-white">
                        Ready to Get Started?
                    </h2>
                    <p class="mb-6 font-light text-gray-500 dark:text-gray-400 md:text-lg">
                        Browse our network of expert coaches and book your first session today
                    </p>
                    <Link
                        :href="route('companies.index')"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                    >
                        Browse Coaches
                    </Link>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
