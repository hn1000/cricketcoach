<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    title: String,
});

const mobileMenuOpen = ref(false);
</script>

<template>
    <div class="antialiased bg-white dark:bg-gray-900">
        <!-- Navbar -->
        <nav class="bg-white border-b border-gray-200 px-4 lg:px-6 py-3 dark:bg-gray-800 dark:border-gray-700 sticky top-0 z-50">
            <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl">
                <!-- Logo/Brand -->
                <Link :href="route('home')" class="flex items-center">
                    <svg class="w-8 h-8 mr-2 text-blue-600 dark:text-blue-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="self-center text-xl font-bold whitespace-nowrap dark:text-white">Cricket Coach</span>
                </Link>

                <!-- Right side buttons -->
                <div class="flex items-center lg:order-2 gap-2">
                    <template v-if="$page.props.auth?.user">
                        <Link
                            :href="route('dashboard')"
                            class="text-gray-800 dark:text-white hover:bg-gray-50 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2 dark:hover:bg-gray-700 focus:outline-none dark:focus:ring-gray-800"
                        >
                            Dashboard
                        </Link>
                    </template>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="text-gray-800 dark:text-white hover:bg-gray-50 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-4 py-2 dark:hover:bg-gray-700 focus:outline-none dark:focus:ring-gray-800"
                        >
                            Log in
                        </Link>
                        <Link
                            :href="route('register')"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                        >
                            Sign up
                        </Link>
                    </template>

                    <!-- Mobile menu button -->
                    <button
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        type="button"
                        class="inline-flex items-center p-2 ml-1 text-sm text-gray-500 rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                    >
                        <span class="sr-only">Toggle menu</span>
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>

                <!-- Navigation Links -->
                <div
                    :class="{ 'hidden': !mobileMenuOpen }"
                    class="justify-between items-center w-full lg:flex lg:w-auto lg:order-1"
                >
                    <ul class="flex flex-col mt-4 font-medium lg:flex-row lg:space-x-8 lg:mt-0">
                        <li>
                            <Link
                                :href="route('home')"
                                class="block py-2 pr-4 pl-3 rounded lg:p-0"
                                :class="route().current('home')
                                    ? 'text-blue-700 dark:text-blue-500'
                                    : 'text-gray-700 hover:text-blue-700 dark:text-gray-400 dark:hover:text-blue-500'"
                            >
                                Home
                            </Link>
                        </li>
                        <li>
                            <Link
                                :href="route('companies.index')"
                                class="block py-2 pr-4 pl-3 rounded lg:p-0"
                                :class="route().current('companies.*')
                                    ? 'text-blue-700 dark:text-blue-500'
                                    : 'text-gray-700 hover:text-blue-700 dark:text-gray-400 dark:hover:text-blue-500'"
                            >
                                Browse Academies
                            </Link>
                        </li>
                        <li>
                            <a
                                href="#how-it-works"
                                class="block py-2 pr-4 pl-3 text-gray-700 rounded hover:text-blue-700 lg:p-0 dark:text-gray-400 dark:hover:text-blue-500"
                            >
                                How It Works
                            </a>
                        </li>
                        <li>
                            <a
                                href="#about"
                                class="block py-2 pr-4 pl-3 text-gray-700 rounded hover:text-blue-700 lg:p-0 dark:text-gray-400 dark:hover:text-blue-500"
                            >
                                About
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main content -->
        <main class="min-h-screen">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
            <div class="max-w-screen-xl p-4 py-6 mx-auto lg:py-16 md:p-8 lg:p-10">
                <div class="grid gap-8 lg:grid-cols-4 md:grid-cols-2 mb-8">
                    <!-- About Column -->
                    <div class="lg:col-span-2">
                        <Link :href="route('home')" class="flex items-center mb-4">
                            <svg class="w-8 h-8 mr-2 text-blue-600 dark:text-blue-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="self-center text-xl font-bold whitespace-nowrap dark:text-white">Cricket Coach</span>
                        </Link>
                        <p class="mb-4 text-gray-500 dark:text-gray-400">
                            Connect with expert cricket coaches and academies. Book personalized coaching sessions to elevate your game.
                        </p>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="mb-4 text-sm font-semibold text-gray-900 uppercase dark:text-white">Quick Links</h3>
                        <ul class="text-gray-500 dark:text-gray-400">
                            <li class="mb-2">
                                <Link :href="route('home')" class="hover:underline">Home</Link>
                            </li>
                            <li class="mb-2">
                                <Link :href="route('companies.index')" class="hover:underline">Browse Academies</Link>
                            </li>
                            <li class="mb-2">
                                <a href="#how-it-works" class="hover:underline">How It Works</a>
                            </li>
                            <li class="mb-2">
                                <a href="#about" class="hover:underline">About Us</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Support -->
                    <div>
                        <h3 class="mb-4 text-sm font-semibold text-gray-900 uppercase dark:text-white">Support</h3>
                        <ul class="text-gray-500 dark:text-gray-400">
                            <li class="mb-2">
                                <a href="#contact" class="hover:underline">Contact Us</a>
                            </li>
                            <li class="mb-2">
                                <a href="#faq" class="hover:underline">FAQs</a>
                            </li>
                            <li class="mb-2">
                                <a href="#privacy" class="hover:underline">Privacy Policy</a>
                            </li>
                            <li class="mb-2">
                                <a href="#terms" class="hover:underline">Terms of Service</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <hr class="my-6 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-8">

                <div class="text-center">
                    <span class="block text-sm text-center text-gray-500 dark:text-gray-400">
                        © 2025 Cricket Coach. All Rights Reserved.
                    </span>
                </div>
            </div>
        </footer>
    </div>
</template>
