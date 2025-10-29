<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import CompanyLayout from '@/Layouts/Sites/Company/Layout.vue';

const props = defineProps({
    companies: Array,
});

const form = useForm({
    company_id: props.companies.length === 1 ? props.companies[0].id : '',
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    position: '',
    department: '',
    bio: '',
});

const submit = () => {
    form.post(route('company.staff.store'));
};
</script>

<template>
    <Head title="Add Coach" />

    <CompanyLayout>
        <div class="mb-4">
            <Link
                :href="route('company.staff.index')"
                class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
            >
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                </svg>
                Back to Coaches
            </Link>
            <h1 class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">Add New Coach</h1>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <form @submit.prevent="submit" class="space-y-6">
                <!-- Company Selection -->
                <div v-if="companies.length > 1">
                    <label for="company_id" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Company <span class="text-red-600">*</span>
                    </label>
                    <select
                        id="company_id"
                        v-model="form.company_id"
                        class="w-full px-4 py-2 text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        :class="{ 'border-red-500': form.errors.company_id }"
                        required
                    >
                        <option value="">Select a company</option>
                        <option v-for="company in companies" :key="company.id" :value="company.id">
                            {{ company.name }}
                        </option>
                    </select>
                    <p v-if="form.errors.company_id" class="mt-2 text-sm text-red-600">{{ form.errors.company_id }}</p>
                </div>

                <!-- Name Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                            First Name <span class="text-red-600">*</span>
                        </label>
                        <input
                            type="text"
                            id="first_name"
                            v-model="form.first_name"
                            class="w-full px-4 py-2 text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            :class="{ 'border-red-500': form.errors.first_name }"
                            required
                        />
                        <p v-if="form.errors.first_name" class="mt-2 text-sm text-red-600">{{ form.errors.first_name }}</p>
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                            Last Name <span class="text-red-600">*</span>
                        </label>
                        <input
                            type="text"
                            id="last_name"
                            v-model="form.last_name"
                            class="w-full px-4 py-2 text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            :class="{ 'border-red-500': form.errors.last_name }"
                            required
                        />
                        <p v-if="form.errors.last_name" class="mt-2 text-sm text-red-600">{{ form.errors.last_name }}</p>
                    </div>
                </div>

                <!-- Contact Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                            Email <span class="text-red-600">*</span>
                        </label>
                        <input
                            type="email"
                            id="email"
                            v-model="form.email"
                            class="w-full px-4 py-2 text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            :class="{ 'border-red-500': form.errors.email }"
                            required
                        />
                        <p v-if="form.errors.email" class="mt-2 text-sm text-red-600">{{ form.errors.email }}</p>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                            Phone
                        </label>
                        <input
                            type="tel"
                            id="phone"
                            v-model="form.phone"
                            class="w-full px-4 py-2 text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            :class="{ 'border-red-500': form.errors.phone }"
                        />
                        <p v-if="form.errors.phone" class="mt-2 text-sm text-red-600">{{ form.errors.phone }}</p>
                    </div>
                </div>

                <!-- Position and Department -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="position" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                            Position/Title
                        </label>
                        <input
                            type="text"
                            id="position"
                            v-model="form.position"
                            placeholder="e.g., Head Coach, Batting Specialist"
                            class="w-full px-4 py-2 text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        />
                    </div>

                    <div>
                        <label for="department" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                            Department/Specialty
                        </label>
                        <input
                            type="text"
                            id="department"
                            v-model="form.department"
                            placeholder="e.g., Batting, Bowling, Fielding"
                            class="w-full px-4 py-2 text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        />
                    </div>
                </div>

                <!-- Bio -->
                <div>
                    <label for="bio" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Biography
                    </label>
                    <textarea
                        id="bio"
                        v-model="form.bio"
                        rows="4"
                        placeholder="Tell customers about this coach's experience, qualifications, and coaching philosophy..."
                        class="w-full px-4 py-2 text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    ></textarea>
                </div>

                <!-- Submit Buttons -->
                <div class="flex gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-6 py-3 bg-green-700 hover:bg-green-800 disabled:bg-green-400 text-white font-medium rounded-lg focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 disabled:cursor-not-allowed"
                    >
                        <span v-if="form.processing">Saving...</span>
                        <span v-else>Add Coach</span>
                    </button>
                    <Link
                        :href="route('company.staff.index')"
                        class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-900 font-medium rounded-lg dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white"
                    >
                        Cancel
                    </Link>
                </div>
            </form>
        </div>
    </CompanyLayout>
</template>
