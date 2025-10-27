<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import Layout from '@/Layouts/Sites/Customer/Layout.vue';
import { ref } from 'vue';

const props = defineProps({
    user: Object,
});

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    current_password: '',
    password: '',
    password_confirmation: '',
});

const showPasswordFields = ref(false);

const updateProfile = () => {
    form.put(route('customer.profile.update'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('current_password', 'password', 'password_confirmation');
            showPasswordFields.value = false;
        },
    });
};
</script>

<template>
    <Layout>
        <Head title="Edit Profile" />

        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Profile Settings</h1>
            <p class="text-gray-600 dark:text-gray-400">Update your account information and password</p>
        </div>

        <div class="max-w-2xl">
            <form @submit.prevent="updateProfile" class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <!-- Basic Information -->
                <div class="p-6 space-y-6">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Basic Information</h2>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Name
                                </label>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                    :class="{ 'border-red-500': form.errors.name }"
                                />
                                <p v-if="form.errors.name" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                    {{ form.errors.name }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Email Address
                                </label>
                                <input
                                    v-model="form.email"
                                    type="email"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                    :class="{ 'border-red-500': form.errors.email }"
                                />
                                <p v-if="form.errors.email" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                    {{ form.errors.email }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Password Section -->
                    <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Change Password</h2>
                            <button
                                type="button"
                                @click="showPasswordFields = !showPasswordFields"
                                class="text-sm text-blue-600 hover:underline dark:text-blue-400"
                            >
                                {{ showPasswordFields ? 'Hide' : 'Show' }} Password Fields
                            </button>
                        </div>

                        <div v-if="showPasswordFields" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Current Password
                                </label>
                                <input
                                    v-model="form.current_password"
                                    type="password"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                    :class="{ 'border-red-500': form.errors.current_password }"
                                />
                                <p v-if="form.errors.current_password" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                    {{ form.errors.current_password }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    New Password
                                </label>
                                <input
                                    v-model="form.password"
                                    type="password"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                    :class="{ 'border-red-500': form.errors.password }"
                                />
                                <p v-if="form.errors.password" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                    {{ form.errors.password }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Confirm New Password
                                </label>
                                <input
                                    v-model="form.password_confirmation"
                                    type="password"
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Success Message -->
                <div v-if="$page.props.flash.success" class="px-6 py-3 bg-green-50 dark:bg-green-900/20 border-t border-green-200 dark:border-green-800">
                    <p class="text-sm text-green-700 dark:text-green-400">
                        {{ $page.props.flash.success }}
                    </p>
                </div>

                <!-- Actions -->
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 flex justify-end space-x-3">
                    <button
                        type="button"
                        @click="form.reset()"
                        class="px-4 py-2 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-500 transition"
                    >
                        Reset
                    </button>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition disabled:opacity-50"
                    >
                        {{ form.processing ? 'Saving...' : 'Save Changes' }}
                    </button>
                </div>
            </form>
        </div>
    </Layout>
</template>
