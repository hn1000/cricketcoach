<script setup>
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/Sites/Public/Layout.vue';
</script>

<template>
    <Head title="Terms of Service" />

    <PublicLayout>
        <section class="bg-white dark:bg-gray-900">
            <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
                <div class="mx-auto max-w-screen-md">
                    <h1 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">Terms of Service</h1>
                    <p class="mb-6 text-gray-500 dark:text-gray-400">Last updated: January 2025</p>

                    <div class="space-y-6 text-gray-500 dark:text-gray-400">
                        <div>
                            <h2 class="mb-2 text-2xl font-bold text-gray-900 dark:text-white">Acceptance of Terms</h2>
                            <p>By accessing and using Cricket Coach, you accept and agree to be bound by the terms and provision of this agreement.</p>
                        </div>

                        <div>
                            <h2 class="mb-2 text-2xl font-bold text-gray-900 dark:text-white">Booking and Payments</h2>
                            <p>All bookings are subject to availability and confirmation. Payment is required at the time of booking. We accept all major credit cards and secure payment methods.</p>
                        </div>

                        <div>
                            <h2 class="mb-2 text-2xl font-bold text-gray-900 dark:text-white">Cancellation Policy</h2>
                            <p>Sessions may be cancelled up to 24 hours before the scheduled time for a full refund. Late cancellations or no-shows may not be eligible for refunds.</p>
                        </div>

                        <div>
                            <h2 class="mb-2 text-2xl font-bold text-gray-900 dark:text-white">User Conduct</h2>
                            <p>You agree to use Cricket Coach only for lawful purposes and in a way that does not infringe the rights of others or restrict their use of the platform.</p>
                        </div>

                        <div>
                            <h2 class="mb-2 text-2xl font-bold text-gray-900 dark:text-white">Limitation of Liability</h2>
                            <p>Cricket Coach is not liable for any injuries, losses, or damages incurred during coaching sessions. All coaching activities are undertaken at your own risk.</p>
                        </div>

                        <div>
                            <h2 class="mb-2 text-2xl font-bold text-gray-900 dark:text-white">Changes to Terms</h2>
                            <p>We reserve the right to modify these terms at any time. Continued use of the platform after changes constitutes acceptance of the new terms.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
