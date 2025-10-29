<script setup>
import PublicLayout from '@/Layouts/Sites/Public/Layout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    company: Object,
    staff: Object,
});

const page = usePage();

// Check if user is authenticated
const isAuthenticated = computed(() => page.props.auth?.user !== null);
const currentUser = computed(() => page.props.auth?.user);

// Form data
const form = ref({
    message: '',
    preferred_date: '',
    preferred_time_slot: '',
});

const errors = ref({});
const submitting = ref(false);

// Time slot options
const timeSlots = [
    { value: '', label: 'No preference' },
    { value: 'morning', label: 'Morning (6am - 12pm)' },
    { value: 'afternoon', label: 'Afternoon (12pm - 6pm)' },
    { value: 'evening', label: 'Evening (6pm - 10pm)' },
];

// Get minimum date (today)
const minDate = computed(() => {
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
});

const submitEnquiry = () => {
    if (submitting.value) return;

    // Validate message
    errors.value = {};
    if (!form.value.message.trim()) {
        errors.value.message = 'Please enter your message';
        return;
    }

    submitting.value = true;

    // Use Inertia's router for proper session handling
    router.post(route('enquiry.store', {
        company: props.company.id,
        staff: props.staff.id,
    }), form.value, {
        onSuccess: () => {
            // Controller will redirect to success page
        },
        onError: (pageErrors) => {
            console.error('Enquiry submission failed:', pageErrors);
            errors.value = pageErrors;
            submitting.value = false;
        },
        onFinish: () => {
            // This runs after both success and error
        }
    });
};

// Redirect to login with intended URL
const redirectToLogin = () => {
    const intended = route('enquiry.create', {
        company: props.company.id,
        staff: props.staff.id,
    });
    router.visit(route('login'), {
        data: { intended },
    });
};
</script>

<template>
    <Head :title="`Send Enquiry - ${staff.first_name} ${staff.last_name}`" />

    <PublicLayout>
        <!-- Breadcrumb -->
        <section class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <Link :href="route('home')" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-green-600 dark:text-gray-400 dark:hover:text-white">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                                </svg>
                                Home
                            </Link>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <Link :href="route('companies.index')" class="ml-1 text-sm font-medium text-gray-700 hover:text-green-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">
                                    Academies
                                </Link>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <Link :href="route('companies.show', company.id)" class="ml-1 text-sm font-medium text-gray-700 hover:text-green-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">
                                    {{ company.name }}
                                </Link>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Send Enquiry</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
        </section>

        <!-- Main Content -->
        <section class="bg-gradient-to-b from-white to-gray-50 dark:from-gray-900 dark:to-gray-800 py-12">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white mb-3">
                        Send Coaching Enquiry
                    </h1>
                    <p class="text-lg text-gray-600 dark:text-gray-400">
                        Get in touch with {{ staff.first_name }} {{ staff.last_name }} at {{ company.name }}
                    </p>
                </div>

                <!-- Coach Info Card -->
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6 mb-8">
                    <div class="flex items-center">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-green-500 to-green-700 flex items-center justify-center text-white text-xl font-bold flex-shrink-0">
                            {{ staff.first_name.charAt(0) }}{{ staff.last_name.charAt(0) }}
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                                {{ staff.first_name }} {{ staff.last_name }}
                            </h3>
                            <p v-if="staff.position" class="text-sm text-gray-600 dark:text-gray-400">
                                {{ staff.position }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ company.name }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Authentication Check -->
                <div v-if="!isAuthenticated" class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-6 mb-8">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div class="ml-3 flex-1">
                            <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-300 mb-2">
                                Login Required
                            </h3>
                            <p class="text-sm text-yellow-700 dark:text-yellow-400 mb-4">
                                You need to be logged in to send an enquiry. This helps coaches respond to you directly.
                            </p>
                            <div class="flex gap-3">
                                <button
                                    @click="redirectToLogin"
                                    class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors focus:ring-4 focus:ring-yellow-300"
                                >
                                    Login to Continue
                                </button>
                                <Link
                                    :href="route('register')"
                                    class="inline-flex items-center px-4 py-2 bg-white hover:bg-gray-50 text-yellow-800 text-sm font-medium rounded-lg border border-yellow-300 transition-colors"
                                >
                                    Create Account
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enquiry Form -->
                <div v-else class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm p-6 md:p-8">
                    <form @submit.prevent="submitEnquiry" class="space-y-6">
                        <!-- Message -->
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                Your Message <span class="text-red-600">*</span>
                            </label>
                            <textarea
                                id="message"
                                v-model="form.message"
                                rows="6"
                                class="w-full px-4 py-3 text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-green-500 dark:focus:border-green-500"
                                :class="{ 'border-red-500 focus:ring-red-500 focus:border-red-500': errors.message }"
                                placeholder="Tell the coach what you're looking for, your experience level, goals, and any questions you have..."
                                required
                            ></textarea>
                            <p v-if="errors.message" class="mt-2 text-sm text-red-600 dark:text-red-500">
                                {{ errors.message }}
                            </p>
                        </div>

                        <!-- Preferred Date (Optional) -->
                        <div>
                            <label for="preferred_date" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                Preferred Date (Optional)
                            </label>
                            <input
                                type="date"
                                id="preferred_date"
                                v-model="form.preferred_date"
                                :min="minDate"
                                class="w-full px-4 py-3 text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-green-500 dark:focus:border-green-500"
                                :class="{ 'border-red-500 focus:ring-red-500 focus:border-red-500': errors.preferred_date }"
                            />
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                Optional: Let the coach know when you'd prefer to have your first session
                            </p>
                            <p v-if="errors.preferred_date" class="mt-2 text-sm text-red-600 dark:text-red-500">
                                {{ errors.preferred_date }}
                            </p>
                        </div>

                        <!-- Preferred Time Slot (Optional) -->
                        <div>
                            <label for="preferred_time_slot" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                Preferred Time Slot (Optional)
                            </label>
                            <select
                                id="preferred_time_slot"
                                v-model="form.preferred_time_slot"
                                class="w-full px-4 py-3 text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-green-500 dark:focus:border-green-500"
                                :class="{ 'border-red-500 focus:ring-red-500 focus:border-red-500': errors.preferred_time_slot }"
                            >
                                <option v-for="slot in timeSlots" :key="slot.value" :value="slot.value">
                                    {{ slot.label }}
                                </option>
                            </select>
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                Optional: Let the coach know your preferred time of day
                            </p>
                            <p v-if="errors.preferred_time_slot" class="mt-2 text-sm text-red-600 dark:text-red-500">
                                {{ errors.preferred_time_slot }}
                            </p>
                        </div>

                        <!-- Info Box -->
                        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div class="ml-3">
                                    <p class="text-sm text-green-800 dark:text-green-300">
                                        The coach will receive your enquiry via email and will get back to you as soon as possible. This is not a confirmed booking - the coach will contact you to discuss availability and schedule your session.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex gap-4">
                            <button
                                type="submit"
                                :disabled="submitting"
                                class="flex-1 px-6 py-3 bg-green-700 hover:bg-green-800 disabled:bg-green-400 text-white font-medium rounded-lg transition-colors focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 disabled:cursor-not-allowed"
                            >
                                <span v-if="submitting">Sending...</span>
                                <span v-else>Send Enquiry</span>
                            </button>
                            <Link
                                :href="route('companies.show', company.id)"
                                class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 font-medium rounded-lg transition-colors dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white"
                            >
                                Cancel
                            </Link>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
