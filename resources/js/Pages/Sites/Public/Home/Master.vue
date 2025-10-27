<script setup>
import PublicLayout from '@/Layouts/Sites/Public/Layout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    companies: Object,
});

const searchQuery = ref('');
const selectedLocation = ref('');

const handleSearch = () => {
    router.get(route('companies.index'), {
        search: searchQuery.value,
        location: selectedLocation.value,
    });
};

const featuredCompanies = (companies) => {
    return companies.data.slice(0, 6);
};
</script>

<template>
    <Head title="Find Your Perfect Cricket Coach" />

    <PublicLayout>
        <!-- Hero Section with Search -->
        <section class="bg-gradient-to-b from-white to-gray-50 dark:from-gray-900 dark:to-gray-800">
            <div class="py-12 px-4 mx-auto max-w-screen-xl text-center lg:py-20">
                <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white">
                    Find Your Perfect Cricket Coach
                </h1>
                <p class="mb-8 text-lg font-normal text-gray-500 lg:text-xl sm:px-16 xl:px-48 dark:text-gray-400">
                    Connect with expert cricket coaches and academies. Book personalized coaching sessions to elevate your game.
                </p>

                <!-- Search Bar -->
                <div class="max-w-3xl mx-auto">
                    <form @submit.prevent="handleSearch" class="flex flex-col md:flex-row gap-4 mb-8">
                        <div class="flex-1">
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Search for coaches, academies, or specialties..."
                                class="w-full px-4 py-3 text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-4 focus:ring-blue-300 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            />
                        </div>
                        <div class="w-full md:w-64">
                            <input
                                v-model="selectedLocation"
                                type="text"
                                placeholder="Location"
                                class="w-full px-4 py-3 text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-4 focus:ring-blue-300 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            />
                        </div>
                        <button
                            type="submit"
                            class="px-6 py-3 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-base whitespace-nowrap dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        >
                            Search
                        </button>
                    </form>
                </div>

                <div class="flex flex-wrap justify-center gap-2 mb-4">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Popular searches:</span>
                    <Link :href="route('companies.index', { search: 'batting' })" class="text-sm text-blue-600 hover:underline dark:text-blue-500">Batting</Link>
                    <span class="text-gray-300 dark:text-gray-600">•</span>
                    <Link :href="route('companies.index', { search: 'bowling' })" class="text-sm text-blue-600 hover:underline dark:text-blue-500">Bowling</Link>
                    <span class="text-gray-300 dark:text-gray-600">•</span>
                    <Link :href="route('companies.index', { search: 'junior' })" class="text-sm text-blue-600 hover:underline dark:text-blue-500">Junior Coaching</Link>
                    <span class="text-gray-300 dark:text-gray-600">•</span>
                    <Link :href="route('companies.index', { search: 'wicket-keeping' })" class="text-sm text-blue-600 hover:underline dark:text-blue-500">Wicket-keeping</Link>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section class="bg-white dark:bg-gray-900 py-12">
            <div class="py-8 px-4 mx-auto max-w-screen-xl sm:py-12 lg:px-6">
                <div class="mb-8 max-w-screen-md text-center mx-auto">
                    <h2 class="mb-4 text-4xl font-extrabold text-gray-900 dark:text-white">
                        How It Works
                    </h2>
                    <p class="text-gray-500 sm:text-xl dark:text-gray-400">
                        Getting started with cricket coaching is simple
                    </p>
                </div>

                <div class="grid gap-8 lg:grid-cols-4 md:grid-cols-2">
                    <div class="text-center">
                        <div class="flex justify-center items-center mx-auto mb-4 w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-900">
                            <svg class="w-8 h-8 text-blue-600 dark:text-blue-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">1. Browse Coaches</h3>
                        <p class="text-gray-500 dark:text-gray-400">
                            Explore our directory of qualified cricket coaches and academies
                        </p>
                    </div>

                    <div class="text-center">
                        <div class="flex justify-center items-center mx-auto mb-4 w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-900">
                            <svg class="w-8 h-8 text-blue-600 dark:text-blue-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                            </svg>
                        </div>
                        <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">2. View Profiles</h3>
                        <p class="text-gray-500 dark:text-gray-400">
                            Read coach bios, specialties, and availability to find the right fit
                        </p>
                    </div>

                    <div class="text-center">
                        <div class="flex justify-center items-center mx-auto mb-4 w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-900">
                            <svg class="w-8 h-8 text-blue-600 dark:text-blue-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">3. Book Session</h3>
                        <p class="text-gray-500 dark:text-gray-400">
                            Select a time slot and book your coaching session online
                        </p>
                    </div>

                    <div class="text-center">
                        <div class="flex justify-center items-center mx-auto mb-4 w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-900">
                            <svg class="w-8 h-8 text-blue-600 dark:text-blue-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">4. Start Training</h3>
                        <p class="text-gray-500 dark:text-gray-400">
                            Show up and begin improving your cricket skills
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Companies -->
        <section class="bg-gray-50 dark:bg-gray-800 py-12">
            <div class="py-8 px-4 mx-auto max-w-screen-xl sm:py-12 lg:px-6">
                <div class="mb-8 max-w-screen-md">
                    <h2 class="mb-4 text-4xl font-extrabold text-gray-900 dark:text-white">
                        Featured Cricket Academies
                    </h2>
                    <p class="text-gray-500 sm:text-xl dark:text-gray-400">
                        Top-rated coaching academies ready to help you improve your game
                    </p>
                </div>

                <div v-if="companies.data.length === 0" class="text-center py-12">
                    <p class="text-gray-500 dark:text-gray-400">No academies available yet.</p>
                </div>

                <div v-else class="grid gap-6 lg:grid-cols-3 md:grid-cols-2">
                    <Link
                        v-for="company in featuredCompanies(companies)"
                        :key="company.id"
                        :href="route('companies.show', company.id)"
                        class="block p-6 bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-lg transition-shadow dark:bg-gray-900 dark:border-gray-700"
                    >
                        <div v-if="company.logo" class="mb-4">
                            <img :src="company.logo" :alt="company.name" class="h-16 object-contain">
                        </div>
                        <h3 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                            {{ company.name }}
                        </h3>
                        <p v-if="company.description" class="mb-4 font-light text-gray-500 dark:text-gray-400 line-clamp-3">
                            {{ company.description }}
                        </p>
                        <div class="flex items-center text-blue-600 dark:text-blue-500 font-medium">
                            View Details
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </Link>
                </div>

                <div class="mt-12 text-center">
                    <Link
                        :href="route('companies.index')"
                        class="inline-flex items-center px-6 py-3 text-base font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                    >
                        Browse All Academies
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </Link>
                </div>
            </div>
        </section>

        <!-- Statistics Banner -->
        <section class="bg-white dark:bg-gray-900 py-12">
            <div class="max-w-screen-xl px-4 py-8 mx-auto text-center lg:py-12 lg:px-6">
                <dl class="grid max-w-screen-md gap-8 mx-auto text-gray-900 sm:grid-cols-3 dark:text-white">
                    <div class="flex flex-col items-center justify-center">
                        <dt class="mb-2 text-3xl md:text-4xl font-extrabold">{{ companies.data.length }}+</dt>
                        <dd class="font-light text-gray-500 dark:text-gray-400">Academies</dd>
                    </div>
                    <div class="flex flex-col items-center justify-center">
                        <dt class="mb-2 text-3xl md:text-4xl font-extrabold">500+</dt>
                        <dd class="font-light text-gray-500 dark:text-gray-400">Sessions Booked</dd>
                    </div>
                    <div class="flex flex-col items-center justify-center">
                        <dt class="mb-2 text-3xl md:text-4xl font-extrabold">4.9</dt>
                        <dd class="font-light text-gray-500 dark:text-gray-400">Average Rating</dd>
                    </div>
                </dl>
            </div>
        </section>

        <!-- Testimonials -->
        <section class="bg-gray-50 dark:bg-gray-800 py-12">
            <div class="py-8 px-4 mx-auto max-w-screen-xl sm:py-12 lg:px-6">
                <div class="mb-8 max-w-screen-md text-center mx-auto">
                    <h2 class="mb-4 text-4xl font-extrabold text-gray-900 dark:text-white">
                        What Our Players Say
                    </h2>
                    <p class="text-gray-500 sm:text-xl dark:text-gray-400">
                        Real feedback from cricketers improving their game
                    </p>
                </div>

                <div class="grid gap-8 lg:grid-cols-3">
                    <div class="p-6 bg-white rounded-lg border border-gray-200 shadow-sm dark:bg-gray-900 dark:border-gray-700">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-lg mr-4">
                                JD
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900 dark:text-white">James Davies</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Amateur Cricketer</p>
                            </div>
                        </div>
                        <div class="flex mb-3">
                            <svg v-for="i in 5" :key="i" class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400">
                            "The booking system made it so easy to find a coach near me. My batting technique has improved dramatically in just 3 months!"
                        </p>
                    </div>

                    <div class="p-6 bg-white rounded-lg border border-gray-200 shadow-sm dark:bg-gray-900 dark:border-gray-700">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center text-white font-semibold text-lg mr-4">
                                SP
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900 dark:text-white">Sarah Patel</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Junior Player Parent</p>
                            </div>
                        </div>
                        <div class="flex mb-3">
                            <svg v-for="i in 5" :key="i" class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400">
                            "Fantastic experience for my daughter. The coaches are professional and the online booking system is very convenient."
                        </p>
                    </div>

                    <div class="p-6 bg-white rounded-lg border border-gray-200 shadow-sm dark:bg-gray-900 dark:border-gray-700">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-lg mr-4">
                                MK
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900 dark:text-white">Michael Kumar</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Club Cricketer</p>
                            </div>
                        </div>
                        <div class="flex mb-3">
                            <svg v-for="i in 5" :key="i" class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400">
                            "Great selection of qualified coaches. I found exactly what I needed to work on my spin bowling technique."
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="bg-white dark:bg-gray-900 py-12">
            <div class="py-8 px-4 mx-auto max-w-screen-xl sm:py-12 lg:px-6">
                <div class="max-w-screen-md mx-auto text-center">
                    <h2 class="mb-4 text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white">
                        Ready to Elevate Your Game?
                    </h2>
                    <p class="mb-8 text-gray-500 sm:text-xl dark:text-gray-400">
                        Join hundreds of cricketers improving their skills with expert coaching
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <Link
                            :href="route('companies.index')"
                            class="inline-flex items-center justify-center px-6 py-3 text-base font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        >
                            Find a Coach
                        </Link>
                        <Link
                            v-if="!$page.props.auth?.user"
                            :href="route('register')"
                            class="inline-flex items-center justify-center px-6 py-3 text-base font-medium text-center text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 dark:text-white dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                        >
                            Sign Up Free
                        </Link>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
