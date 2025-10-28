<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/Sites/Public/Layout.vue';

const form = useForm({
    name: '',
    email: '',
    subject: '',
    message: '',
});

const submit = () => {
    form.post(route('contact.submit'), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Contact Us" />

    <PublicLayout>
        <section class="bg-white dark:bg-gray-900">
            <div class="py-8 lg:py-16 px-4 mx-auto max-w-screen-md">
                <h1 class="mb-4 text-4xl tracking-tight font-extrabold text-center text-gray-900 dark:text-white">Contact Us</h1>
                <p class="mb-8 lg:mb-16 font-light text-center text-gray-500 dark:text-gray-400 sm:text-xl">
                    Have a question? Need help? We're here to assist you.
                </p>

                <div v-if="$page.props.flash?.success" class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-green-900/20 dark:text-green-400">
                    {{ $page.props.flash.success }}
                </div>

                <form @submit.prevent="submit" class="space-y-8">
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Your name</label>
                        <input v-model="form.name" type="text" id="name" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required />
                        <p v-if="form.errors.name" class="mt-2 text-sm text-red-600 dark:text-red-400">{{ form.errors.name }}</p>
                    </div>
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Your email</label>
                        <input v-model="form.email" type="email" id="email" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required />
                        <p v-if="form.errors.email" class="mt-2 text-sm text-red-600 dark:text-red-400">{{ form.errors.email }}</p>
                    </div>
                    <div>
                        <label for="subject" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Subject</label>
                        <input v-model="form.subject" type="text" id="subject" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required />
                        <p v-if="form.errors.subject" class="mt-2 text-sm text-red-600 dark:text-red-400">{{ form.errors.subject }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Your message</label>
                        <textarea v-model="form.message" id="message" rows="6" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required></textarea>
                        <p v-if="form.errors.message" class="mt-2 text-sm text-red-600 dark:text-red-400">{{ form.errors.message }}</p>
                    </div>
                    <button type="submit" :disabled="form.processing" class="py-3 px-5 text-sm font-medium text-center text-white rounded-lg bg-blue-700 sm:w-fit hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" :class="{ 'opacity-25': form.processing }">
                        Send message
                    </button>
                </form>
            </div>
        </section>
    </PublicLayout>
</template>
