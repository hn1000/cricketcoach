<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    title: String,
});
</script>

<template>
    <div class="antialiased bg-white dark:bg-gray-900">
        <!-- Navbar -->
        <nav class="bg-white border-gray-200 px-4 lg:px-6 py-2.5 dark:bg-gray-800">
            <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl">
                <Link :href="route('home')" class="flex items-center">
                    <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">Company Directory</span>
                </Link>
                <div class="flex items-center lg:order-2">
                    <Link
                        v-if="$page.props.auth?.user"
                        :href="route('dashboard')"
                        class="text-gray-800 dark:text-white hover:bg-gray-50 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-4 lg:px-5 py-2 lg:py-2.5 mr-2 dark:hover:bg-gray-700 focus:outline-none dark:focus:ring-gray-800"
                    >
                        Dashboard
                    </Link>
                    <Link
                        v-else
                        :href="route('login')"
                        class="text-gray-800 dark:text-white hover:bg-gray-50 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-4 lg:px-5 py-2 lg:py-2.5 mr-2 dark:hover:bg-gray-700 focus:outline-none dark:focus:ring-gray-800"
                    >
                        Log in
                    </Link>
                </div>
            </div>
        </nav>

        <!-- Main content -->
        <main class="min-h-screen">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-white dark:bg-gray-800">
            <div class="max-w-screen-xl p-4 py-6 mx-auto lg:py-16 md:p-8 lg:p-10">
                <hr class="my-6 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-8">
                <div class="text-center">
                    <span class="block text-sm text-center text-gray-500 dark:text-gray-400">© 2025 Company Directory. All Rights Reserved.</span>
                </div>
            </div>
        </footer>
    </div>
</template>
