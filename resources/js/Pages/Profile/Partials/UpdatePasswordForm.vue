<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('user-password.update'), {
        errorBag: 'updatePassword',
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }

            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <div class="bg-white shadow dark:bg-gray-800 sm:rounded-lg">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Update Password</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Ensure your account is using a long, random password to stay secure.</p>
        </div>

        <form @submit.prevent="updatePassword" class="p-4">
            <div class="space-y-4">
                <!-- Current Password -->
                <div>
                    <label for="current_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Current Password
                    </label>
                    <input
                        id="current_password"
                        ref="currentPasswordInput"
                        v-model="form.current_password"
                        type="password"
                        autocomplete="current-password"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        :class="{ 'border-red-500 dark:border-red-500': form.errors.current_password }"
                    />
                    <p v-if="form.errors.current_password" class="mt-2 text-sm text-red-600 dark:text-red-500">
                        {{ form.errors.current_password }}
                    </p>
                </div>

                <!-- New Password -->
                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        New Password
                    </label>
                    <input
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        autocomplete="new-password"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        :class="{ 'border-red-500 dark:border-red-500': form.errors.password }"
                    />
                    <p v-if="form.errors.password" class="mt-2 text-sm text-red-600 dark:text-red-500">
                        {{ form.errors.password }}
                    </p>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Confirm New Password
                    </label>
                    <input
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        autocomplete="new-password"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        :class="{ 'border-red-500 dark:border-red-500': form.errors.password_confirmation }"
                    />
                    <p v-if="form.errors.password_confirmation" class="mt-2 text-sm text-red-600 dark:text-red-500">
                        {{ form.errors.password_confirmation }}
                    </p>
                </div>
            </div>

            <div class="flex items-center justify-end mt-6">
                <span v-if="form.recentlySuccessful" class="text-sm text-green-600 dark:text-green-400 mr-3">
                    Password updated successfully.
                </span>
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 disabled:opacity-50"
                >
                    Update Password
                </button>
            </div>
        </form>
    </div>
</template>
