<script setup>
import { computed } from 'vue';

const props = defineProps({
    currentDate: {
        type: Date,
        required: true
    },
    view: {
        type: String,
        default: 'week',
        validator: (value) => ['month', 'week', 'day'].includes(value)
    }
});

const emit = defineEmits(['update:currentDate', 'update:view', 'today']);

const formattedDate = computed(() => {
    const options = { month: 'long', year: 'numeric' };
    if (props.view === 'day') {
        options.day = 'numeric';
    }
    return props.currentDate.toLocaleDateString('en-US', options);
});

const previousPeriod = () => {
    const newDate = new Date(props.currentDate);
    if (props.view === 'month') {
        newDate.setMonth(newDate.getMonth() - 1);
    } else if (props.view === 'week') {
        newDate.setDate(newDate.getDate() - 7);
    } else if (props.view === 'day') {
        newDate.setDate(newDate.getDate() - 1);
    }
    emit('update:currentDate', newDate);
};

const nextPeriod = () => {
    const newDate = new Date(props.currentDate);
    if (props.view === 'month') {
        newDate.setMonth(newDate.getMonth() + 1);
    } else if (props.view === 'week') {
        newDate.setDate(newDate.getDate() + 7);
    } else if (props.view === 'day') {
        newDate.setDate(newDate.getDate() + 1);
    }
    emit('update:currentDate', newDate);
};

const goToToday = () => {
    emit('today');
};

const changeView = (newView) => {
    emit('update:view', newView);
};
</script>

<template>
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-4 bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <!-- Date Display and Navigation -->
        <div class="flex items-center gap-2">
            <button
                @click="goToToday"
                class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600"
            >
                Today
            </button>
            <div class="flex items-center gap-1">
                <button
                    @click="previousPeriod"
                    class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg dark:text-gray-400 dark:hover:bg-gray-700"
                >
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </button>
                <button
                    @click="nextPeriod"
                    class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg dark:text-gray-400 dark:hover:bg-gray-700"
                >
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                {{ formattedDate }}
            </h2>
        </div>

        <!-- View Switcher -->
        <div class="inline-flex rounded-lg shadow-sm" role="group">
            <button
                @click="changeView('month')"
                :class="[
                    'px-4 py-2 text-sm font-medium border border-gray-300 rounded-l-lg',
                    view === 'month'
                        ? 'bg-blue-600 text-white border-blue-600 dark:bg-blue-500'
                        : 'bg-white text-gray-700 hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600'
                ]"
            >
                Month
            </button>
            <button
                @click="changeView('week')"
                :class="[
                    'px-4 py-2 text-sm font-medium border-t border-b border-gray-300',
                    view === 'week'
                        ? 'bg-blue-600 text-white border-blue-600 dark:bg-blue-500'
                        : 'bg-white text-gray-700 hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600'
                ]"
            >
                Week
            </button>
            <button
                @click="changeView('day')"
                :class="[
                    'px-4 py-2 text-sm font-medium border border-gray-300 rounded-r-lg',
                    view === 'day'
                        ? 'bg-blue-600 text-white border-blue-600 dark:bg-blue-500'
                        : 'bg-white text-gray-700 hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600'
                ]"
            >
                Day
            </button>
        </div>
    </div>
</template>
