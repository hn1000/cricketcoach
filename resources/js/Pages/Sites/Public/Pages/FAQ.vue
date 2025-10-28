<script setup>
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/Sites/Public/Layout.vue';
import { ref } from 'vue';

const openAccordion = ref(null);

const toggleAccordion = (index) => {
    openAccordion.value = openAccordion.value === index ? null : index;
};

const faqs = [
    {
        category: 'For Players',
        questions: [
            {
                question: 'How do I book a coaching session?',
                answer: 'Simply browse our directory of coaches, select one that fits your needs, choose an available time slot, and complete the booking with secure payment. You\'ll receive instant confirmation via email.'
            },
            {
                question: 'Can I cancel or reschedule my session?',
                answer: 'Yes, you can cancel or reschedule up to 24 hours before your session for a full refund. Simply log into your account and manage your bookings from the dashboard.'
            },
            {
                question: 'What equipment do I need to bring?',
                answer: 'Requirements vary by coach. Check the coach\'s profile or contact them directly for specific equipment needs. Most coaches provide basic equipment, but you should bring your own bat and protective gear.'
            },
        ]
    },
    {
        category: 'Payments',
        questions: [
            {
                question: 'What payment methods do you accept?',
                answer: 'We accept all major credit cards, debit cards, and digital payment methods through our secure payment processor.'
            },
            {
                question: 'When will I be charged?',
                answer: 'Payment is processed immediately upon booking confirmation. You\'ll receive a receipt via email once payment is successful.'
            },
            {
                question: 'How do refunds work?',
                answer: 'If you cancel at least 24 hours before your session, you\'ll receive a full refund within 5-7 business days. Late cancellations may not be eligible for refunds.'
            },
        ]
    },
    {
        category: 'Technical',
        questions: [
            {
                question: 'Do I need to create an account?',
                answer: 'Yes, creating an account allows you to manage your bookings, view your history, and receive important updates about your sessions.'
            },
            {
                question: 'Is my personal information secure?',
                answer: 'Absolutely. We use industry-standard encryption and security measures to protect your personal and payment information. See our Privacy Policy for more details.'
            },
            {
                question: 'I forgot my password. What should I do?',
                answer: 'Click the "Forgot Password" link on the login page. You\'ll receive an email with instructions to reset your password.'
            },
        ]
    },
];
</script>

<template>
    <Head title="FAQ" />

    <PublicLayout>
        <!-- Hero Section -->
        <section class="bg-white dark:bg-gray-900">
            <div class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16">
                <h1 class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white">
                    Frequently Asked Questions
                </h1>
                <p class="mb-8 text-lg font-normal text-gray-500 lg:text-xl sm:px-16 xl:px-48 dark:text-gray-400">
                    Find answers to common questions about booking, payments, and using Cricket Coach
                </p>
            </div>
        </section>

        <!-- FAQ Content -->
        <section class="bg-gray-50 dark:bg-gray-800">
            <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
                <div class="max-w-4xl mx-auto">
                    <div v-for="(category, catIndex) in faqs" :key="catIndex" class="mb-10">
                        <h2 class="mb-6 text-2xl font-bold text-gray-900 dark:text-white">{{ category.category }}</h2>

                        <div class="space-y-4">
                            <div v-for="(faq, index) in category.questions" :key="index" class="bg-white border border-gray-200 rounded-lg dark:bg-gray-900 dark:border-gray-700">
                                <button
                                    @click="toggleAccordion(`${catIndex}-${index}`)"
                                    class="flex items-center justify-between w-full p-5 font-medium text-left text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-800"
                                >
                                    <span>{{ faq.question }}</span>
                                    <svg
                                        :class="{ 'rotate-180': openAccordion === `${catIndex}-${index}` }"
                                        class="w-6 h-6 shrink-0 transition-transform"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                                <div
                                    v-show="openAccordion === `${catIndex}-${index}`"
                                    class="p-5 border-t border-gray-200 dark:border-gray-700"
                                >
                                    <p class="text-gray-500 dark:text-gray-400">{{ faq.answer }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
