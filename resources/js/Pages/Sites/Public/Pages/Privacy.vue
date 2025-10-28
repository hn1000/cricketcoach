<script setup>
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/Sites/Public/Layout.vue';
</script>

<template>
    <Head title="Privacy Policy" />

    <PublicLayout>
        <section class="bg-white dark:bg-gray-900">
            <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
                <div class="mx-auto max-w-screen-md">
                    <h1 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">Privacy Policy</h1>
                    <p class="mb-6 text-gray-500 dark:text-gray-400">Last updated: January 2025</p>

                    <div class="space-y-6 text-gray-500 dark:text-gray-400">
                        <div>
                            <h2 class="mb-2 text-2xl font-bold text-gray-900 dark:text-white">Information We Collect</h2>
                            <p>We collect information you provide directly to us when you create an account, book a session, or contact us. This includes your name, email address, phone number, and payment information.</p>
                        </div>

                        <div>
                            <h2 class="mb-2 text-2xl font-bold text-gray-900 dark:text-white">How We Use Your Information</h2>
                            <p>We use the information we collect to:</p>
                            <ul class="list-disc list-inside mt-2 space-y-1">
                                <li>Process your bookings and payments</li>
                                <li>Send booking confirmations and reminders</li>
                                <li>Communicate with you about our services</li>
                                <li>Improve our platform and user experience</li>
                            </ul>
                        </div>

                        <div>
                            <h2 class="mb-2 text-2xl font-bold text-gray-900 dark:text-white">Data Security</h2>
                            <p>We implement appropriate technical and organizational measures to protect your personal data against unauthorized access, alteration, disclosure, or destruction.</p>
                        </div>

                        <div>
                            <h2 class="mb-2 text-2xl font-bold text-gray-900 dark:text-white">Your Rights</h2>
                            <p>You have the right to access, correct, or delete your personal information. Contact us if you wish to exercise these rights.</p>
                        </div>

                        <div>
                            <h2 class="mb-2 text-2xl font-bold text-gray-900 dark:text-white">Contact Us</h2>
                            <p>If you have questions about this Privacy Policy, please contact us through our contact page.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
