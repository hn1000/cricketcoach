<script setup>
import PublicLayout from '@/Layouts/Sites/Public/Layout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    enquiry: Object,
});

const formatDate = (date) => {
    if (!date) return null;
    return new Date(date).toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};

const timeSlotLabels = {
    morning: 'Morning (6am - 12pm)',
    afternoon: 'Afternoon (12pm - 6pm)',
    evening: 'Evening (6pm - 10pm)',
};
</script>

<template>
    <Head title="Enquiry Sent Successfully" />

    <PublicLayout>
        <section class="bg-gradient-to-b from-white to-gray-50 dark:from-gray-900 dark:to-gray-800 py-12">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Success Icon -->
                <div class="text-center mb-10">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-green-100 dark:bg-green-900/30 mb-6 animate-bounce">
                        <svg class="w-14 h-14 text-green-600 dark:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-3">Enquiry Sent!</h1>
                    <p class="text-lg text-gray-600 dark:text-gray-400">Your message has been sent to the coach</p>
                </div>

                <!-- Enquiry Details Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-lg overflow-hidden mb-6">
                    <div class="bg-gradient-to-r from-green-600 to-green-700 dark:from-green-700 dark:to-green-800 px-6 py-5">
                        <h2 class="text-2xl font-bold text-white">What Happens Next?</h2>
                    </div>

                    <div class="p-6 md:p-8 space-y-6">
                        <!-- Timeline -->
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-700 dark:text-green-400 font-bold text-sm mr-4">
                                    1
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">Coach Receives Your Enquiry</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        {{ enquiry.staff.first_name }} will receive an email notification with your message and contact details
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-700 dark:text-green-400 font-bold text-sm mr-4">
                                    2
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">Coach Reviews and Responds</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        The coach will review your message and get back to you via email or phone
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-green-700 dark:text-green-400 font-bold text-sm mr-4">
                                    3
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">Schedule Your Session</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        Work with the coach to find a suitable time and confirm your coaching session
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Your Enquiry Details</h3>

                            <!-- Coach -->
                            <div class="flex items-start mb-4">
                                <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Coach</p>
                                    <p class="text-lg font-bold text-gray-900 dark:text-white">
                                        {{ enquiry.staff.first_name }} {{ enquiry.staff.last_name }}
                                    </p>
                                    <p class="text-gray-700 dark:text-gray-300">{{ enquiry.company.name }}</p>
                                </div>
                            </div>

                            <!-- Preferred Date/Time -->
                            <div v-if="enquiry.preferred_date || enquiry.preferred_time_slot" class="flex items-start mb-4">
                                <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Your Preferences</p>
                                    <p v-if="enquiry.preferred_date" class="text-gray-900 dark:text-white">
                                        <span class="font-medium">Date:</span> {{ formatDate(enquiry.preferred_date) }}
                                    </p>
                                    <p v-if="enquiry.preferred_time_slot" class="text-gray-900 dark:text-white">
                                        <span class="font-medium">Time:</span> {{ timeSlotLabels[enquiry.preferred_time_slot] }}
                                    </p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                        These are preferences - the coach will discuss actual availability with you
                                    </p>
                                </div>
                            </div>

                            <!-- Message -->
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Your Message</p>
                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 text-gray-900 dark:text-white whitespace-pre-line">
                                        {{ enquiry.message }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6 mb-6">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300 mb-2">
                                Response Time
                            </h3>
                            <p class="text-sm text-blue-700 dark:text-blue-400">
                                Most coaches respond within 24-48 hours. If you don't hear back within 3 days, feel free to contact the academy directly.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <Link
                        :href="route('companies.show', enquiry.company.id)"
                        class="flex-1 text-center px-6 py-3 bg-green-700 hover:bg-green-800 text-white font-medium rounded-lg transition-colors focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800"
                    >
                        Back to {{ enquiry.company.name }}
                    </Link>
                    <Link
                        :href="route('companies.index')"
                        class="flex-1 text-center px-6 py-3 bg-white hover:bg-gray-50 text-gray-900 font-medium rounded-lg border border-gray-300 transition-colors dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white dark:border-gray-600"
                    >
                        Browse Other Coaches
                    </Link>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
