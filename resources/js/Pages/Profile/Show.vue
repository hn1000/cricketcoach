<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AdminLayout from '@/Layouts/Sites/Admin/Layout.vue';
import CustomerLayout from '@/Layouts/Sites/Customer/Layout.vue';
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue';

defineProps({
    confirmsTwoFactorAuthentication: Boolean,
    sessions: Array,
});

const page = usePage();
const user = computed(() => page.props.auth?.user);
const isAdmin = computed(() => ['admin', 'super_admin'].includes(user.value?.role));

// Choose layout based on user role
const LayoutComponent = computed(() => isAdmin.value ? AdminLayout : CustomerLayout);
</script>

<template>
    <Head title="Profile Settings" />

    <component :is="LayoutComponent">
        <!-- Header -->
        <div class="p-4 bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Profile Settings</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage your account information and security settings</p>
        </div>

        <div class="p-4">
            <div class="max-w-4xl space-y-4">
                <!-- Update Profile Information -->
                <div v-if="$page.props.jetstream.canUpdateProfileInformation">
                    <UpdateProfileInformationForm :user="$page.props.auth.user" />
                </div>

                <!-- Update Password -->
                <div v-if="$page.props.jetstream.canUpdatePassword">
                    <UpdatePasswordForm />
                </div>
            </div>
        </div>
    </component>
</template>
