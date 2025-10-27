<script setup>
import { computed } from 'vue';

const props = defineProps({
    event: {
        type: Object,
        required: true
    }
});

const emit = defineEmits(['click']);

const eventStyle = computed(() => {
    return {
        backgroundColor: props.event.backgroundColor || '#3b82f6',
        borderColor: props.event.borderColor || '#2563eb',
        color: props.event.textColor || '#ffffff'
    };
});

const eventClasses = computed(() => {
    const baseClasses = 'px-2 py-1 text-xs rounded border cursor-pointer hover:opacity-90 transition-opacity';

    if (props.event.type === 'available') {
        return `${baseClasses} bg-green-100 border-green-300 text-green-800 dark:bg-green-900/30 dark:border-green-700 dark:text-green-300`;
    } else if (props.event.type === 'exception') {
        return `${baseClasses} bg-red-100 border-red-300 text-red-800 dark:bg-red-900/30 dark:border-red-700 dark:text-red-300`;
    } else if (props.event.type === 'booking') {
        return `${baseClasses} bg-blue-100 border-blue-300 text-blue-800 dark:bg-blue-900/30 dark:border-blue-700 dark:text-blue-300`;
    }

    return baseClasses;
});

const handleClick = () => {
    emit('click', props.event);
};
</script>

<template>
    <div
        :class="eventClasses"
        :title="event.title"
        @click="handleClick"
    >
        <div class="font-medium truncate">{{ event.title }}</div>
        <div v-if="event.metadata?.time" class="text-xs opacity-75 truncate">
            {{ event.metadata.time }}
        </div>
    </div>
</template>
