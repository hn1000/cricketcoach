<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    company: Object,
    activeTab: {
        type: String,
        default: 'dashboard'
    }
});

const tabs = [
    {
        name: 'Dashboard',
        key: 'dashboard',
        route: 'admin.companies.manage.dashboard',
        icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'
    },
    {
        name: 'Staff',
        key: 'staff',
        route: 'admin.companies.staff.index',
        icon: 'M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z'
    },
    {
        name: 'Calendar',
        key: 'calendar',
        route: 'admin.companies.calendar.index',
        icon: 'M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z'
    }
];
</script>

<template>
    <div class="border-b border-gray-200 dark:border-gray-700">
        <nav class="flex -mb-px">
            <Link
                v-for="tab in tabs"
                :key="tab.key"
                :href="route(tab.route, company.id)"
                :class="[
                    activeTab === tab.key
                        ? 'border-blue-500 text-blue-600 dark:text-blue-500'
                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300',
                    'group inline-flex items-center py-4 px-6 border-b-2 font-medium text-sm'
                ]"
            >
                <svg
                    :class="[
                        activeTab === tab.key
                            ? 'text-blue-500 dark:text-blue-500'
                            : 'text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-400',
                        '-ml-0.5 mr-2 h-5 w-5'
                    ]"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path :d="tab.icon"></path>
                </svg>
                <span>{{ tab.name }}</span>
            </Link>
        </nav>
    </div>
</template>
