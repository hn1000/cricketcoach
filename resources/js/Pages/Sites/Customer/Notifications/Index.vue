<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import Layout from '@/Layouts/Sites/Customer/Layout.vue';
import { ref } from 'vue';

const props = defineProps({
    notifications: Object,
    filter: String,
    unreadCount: Number,
});

const markAsRead = async (id) => {
    await router.post(route('customer.notifications.read', id), {}, {
        preserveScroll: true,
        onSuccess: () => router.reload({ only: ['notifications', 'unreadCount'] }),
    });
};

const markAllAsRead = async () => {
    await router.post(route('customer.notifications.read-all'), {}, {
        preserveScroll: true,
        onSuccess: () => router.reload({ only: ['notifications', 'unreadCount'] }),
    });
};

const deleteNotification = async (id) => {
    if (confirm('Are you sure you want to delete this notification?')) {
        await router.delete(route('customer.notifications.destroy', id), {
            preserveScroll: true,
        });
    }
};

const changeFilter = (newFilter) => {
    router.get(route('customer.notifications.index', { filter: newFilter }), {}, {
        preserveState: true,
        replace: true,
    });
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const getNotificationIcon = (type) => {
    // Determine icon based on notification type
    if (type.includes('Booking')) {
        return `<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
        </svg>`;
    } else if (type.includes('Payment')) {
        return `<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
            <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
        </svg>`;
    }
    return `<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
    </svg>`;
};
</script>

<template>
    <Layout>
        <Head title="Notifications" />

        <div class="mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Notifications</h1>
                    <p class="text-gray-600 dark:text-gray-400">Stay updated with your bookings and orders</p>
                </div>
                <button
                    v-if="unreadCount > 0"
                    @click="markAllAsRead"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition"
                >
                    Mark All as Read
                </button>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
            <div class="flex border-b border-gray-200 dark:border-gray-700">
                <button
                    @click="changeFilter('all')"
                    class="px-6 py-3 text-sm font-medium transition"
                    :class="filter === 'all'
                        ? 'border-b-2 border-blue-600 text-blue-600 dark:text-blue-400'
                        : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200'"
                >
                    All
                </button>
                <button
                    @click="changeFilter('unread')"
                    class="px-6 py-3 text-sm font-medium transition relative"
                    :class="filter === 'unread'
                        ? 'border-b-2 border-blue-600 text-blue-600 dark:text-blue-400'
                        : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200'"
                >
                    Unread
                    <span v-if="unreadCount > 0" class="ml-2 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full">
                        {{ unreadCount > 9 ? '9+' : unreadCount }}
                    </span>
                </button>
                <button
                    @click="changeFilter('read')"
                    class="px-6 py-3 text-sm font-medium transition"
                    :class="filter === 'read'
                        ? 'border-b-2 border-blue-600 text-blue-600 dark:text-blue-400'
                        : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200'"
                >
                    Read
                </button>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div v-if="notifications.data.length === 0" class="p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <p class="mt-2 text-gray-500 dark:text-gray-400">No notifications</p>
            </div>

            <div v-else class="divide-y divide-gray-200 dark:divide-gray-700">
                <div
                    v-for="notification in notifications.data"
                    :key="notification.id"
                    class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                    :class="{ 'bg-blue-50 dark:bg-blue-900/10': !notification.read_at }"
                >
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 text-gray-400 dark:text-gray-500" v-html="getNotificationIcon(notification.type)"></div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ notification.data.title || 'Notification' }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        {{ notification.data.message }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                        {{ formatDate(notification.created_at) }}
                                    </p>
                                </div>
                                <div class="flex items-center space-x-2 ml-4">
                                    <button
                                        v-if="!notification.read_at"
                                        @click="markAsRead(notification.id)"
                                        class="p-1 text-blue-600 hover:bg-blue-100 dark:text-blue-400 dark:hover:bg-blue-900/20 rounded transition"
                                        title="Mark as read"
                                    >
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                    <button
                                        @click="deleteNotification(notification.id)"
                                        class="p-1 text-red-600 hover:bg-red-100 dark:text-red-400 dark:hover:bg-red-900/20 rounded transition"
                                        title="Delete"
                                    >
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div v-if="notifications.data.length > 0" class="mt-6 flex justify-between items-center">
            <p class="text-sm text-gray-700 dark:text-gray-300">
                Showing {{ notifications.from }} to {{ notifications.to }} of {{ notifications.total }} notifications
            </p>
            <div class="flex space-x-2">
                <Link
                    v-for="link in notifications.links"
                    :key="link.label"
                    :href="link.url"
                    v-html="link.label"
                    class="px-3 py-2 text-sm rounded-lg"
                    :class="link.active
                        ? 'bg-blue-600 text-white'
                        : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'"
                />
            </div>
        </div>
    </Layout>
</template>
