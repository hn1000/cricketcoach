<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    user: Object,
});

const form = useForm({
    _method: 'PUT',
    name: props.user.name,
    email: props.user.email,
});

const updateProfileInformation = () => {
    form.post(route('user-profile-information.update'), {
        errorBag: 'updateProfileInformation',
        preserveScroll: true,
    });
};
</script>

<template>
    <div class="bg-white shadow dark:bg-gray-800 sm:rounded-lg">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Profile Information</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Update your account's profile information and email address.</p>
        </div>

        <form @submit.prevent="updateProfileInformation" class="p-4">
            <div class="space-y-4">
                <!-- Name -->
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Name
                    </label>
                    <input
                        id="name"
                        v-model="form.name"
                        type="text"
                        required
                        autocomplete="name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        :class="{ 'border-red-500 dark:border-red-500': form.errors.name }"
                    />
                    <p v-if="form.errors.name" class="mt-2 text-sm text-red-600 dark:text-red-500">
                        {{ form.errors.name }}
                    </p>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        Email Address
                    </label>
                    <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        required
                        autocomplete="username"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        :class="{ 'border-red-500 dark:border-red-500': form.errors.email }"
                    />
                    <p v-if="form.errors.email" class="mt-2 text-sm text-red-600 dark:text-red-500">
                        {{ form.errors.email }}
                    </p>
                </div>
            </div>

            <div class="flex items-center justify-end mt-6">
                <span v-if="form.recentlySuccessful" class="text-sm text-green-600 dark:text-green-400 mr-3">
                    Saved successfully.
                </span>
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 disabled:opacity-50"
                >
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</template>
