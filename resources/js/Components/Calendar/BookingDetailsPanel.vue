<script setup>
import { computed } from 'vue';

const props = defineProps({
    event: {
        type: Object,
        default: null
    },
    show: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['close', 'edit', 'cancel', 'complete']);

const formattedDate = computed(() => {
    if (!props.event) return '';
    const date = new Date(props.event.start);
    return date.toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
});

const formattedTime = computed(() => {
    if (!props.event) return '';
    const start = new Date(props.event.start);
    const end = new Date(props.event.end);
    return `${start.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })} - ${end.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}`;
});

const statusColor = computed(() => {
    if (!props.event?.metadata?.status) return 'gray';
    const status = props.event.metadata.status;
    if (status === 'confirmed') return 'blue';
    if (status === 'completed') return 'green';
    if (status === 'cancelled') return 'red';
    if (status === 'no_show') return 'yellow';
    return 'gray';
});

const handleEdit = () => {
    emit('edit', props.event);
};

const handleCancel = () => {
    emit('cancel', props.event);
};

const handleComplete = () => {
    emit('complete', props.event);
};

const handleClose = () => {
    emit('close');
};
</script>

<template>
    <!-- Overlay -->
    <Transition
        enter-active-class="transition-opacity duration-300"
        leave-active-class="transition-opacity duration-300"
        enter-from-class="opacity-0"
        leave-to-class="opacity-0"
    >
        <div
            v-if="show"
            class="fixed inset-0 bg-gray-900/50 dark:bg-gray-900/80 z-40"
            @click="handleClose"
        ></div>
    </Transition>

    <!-- Slide-over Panel -->
    <Transition
        enter-active-class="transition-transform duration-300"
        leave-active-class="transition-transform duration-300"
        enter-from-class="translate-x-full"
        leave-to-class="translate-x-full"
    >
        <div
            v-if="show && event"
            class="fixed inset-y-0 right-0 w-full sm:w-96 bg-white dark:bg-gray-800 shadow-xl z-50 overflow-y-auto"
        >
            <!-- Header -->
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ event.type === 'booking' ? 'Booking Details' : event.type === 'exception' ? 'Exception Details' : 'Availability' }}
                    </h3>
                    <button
                        @click="handleClose"
                        class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"
                    >
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Content -->
            <div class="p-4 space-y-6">
                <!-- Event Title -->
                <div>
                    <h4 class="text-xl font-semibold text-gray-900 dark:text-white">
                        {{ event.title }}
                    </h4>
                </div>

                <!-- Date & Time -->
                <div class="space-y-2">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 mr-3 mt-0.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ formattedDate }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ formattedTime }}</div>
                            <div v-if="event.metadata?.duration" class="text-sm text-gray-600 dark:text-gray-400">
                                Duration: {{ event.metadata.duration }} minutes
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status (for bookings) -->
                <div v-if="event.type === 'booking' && event.metadata?.status" class="space-y-2">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <span
                        :class="[
                            'inline-flex px-2.5 py-0.5 rounded text-xs font-medium',
                            statusColor === 'blue' && 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                            statusColor === 'green' && 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                            statusColor === 'red' && 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                            statusColor === 'yellow' && 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300'
                        ]"
                    >
                        {{ event.metadata.status.charAt(0).toUpperCase() + event.metadata.status.slice(1) }}
                    </span>
                </div>

                <!-- Staff Member -->
                <div v-if="event.metadata?.staffName" class="space-y-2">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Staff Member</label>
                    <div class="text-sm text-gray-900 dark:text-white">{{ event.metadata.staffName }}</div>
                </div>

                <!-- Customer Details (for bookings) -->
                <div v-if="event.type === 'booking'" class="space-y-4">
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <h5 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Customer Information</h5>

                        <div v-if="event.metadata?.customerName" class="space-y-2">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 mr-3 mt-0.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                                <div class="text-sm text-gray-900 dark:text-white">{{ event.metadata.customerName }}</div>
                            </div>
                        </div>

                        <div v-if="event.metadata?.customerEmail" class="space-y-2">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 mr-3 mt-0.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                                <a :href="`mailto:${event.metadata.customerEmail}`" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ event.metadata.customerEmail }}
                                </a>
                            </div>
                        </div>

                        <div v-if="event.metadata?.customerPhone" class="space-y-2">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 mr-3 mt-0.5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                </svg>
                                <a :href="`tel:${event.metadata.customerPhone}`" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ event.metadata.customerPhone }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div v-if="event.metadata?.notes" class="space-y-2">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ event.metadata.notes }}</p>
                </div>

                <!-- Reason (for exceptions) -->
                <div v-if="event.type === 'exception' && event.metadata?.reason" class="space-y-2">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Reason</label>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ event.metadata.reason }}</p>
                </div>
            </div>

            <!-- Actions (for bookings) -->
            <div v-if="event.type === 'booking'" class="p-4 border-t border-gray-200 dark:border-gray-700 space-y-2">
                <button
                    v-if="event.metadata?.status === 'confirmed'"
                    @click="handleComplete"
                    class="w-full px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-300 dark:bg-green-500 dark:hover:bg-green-600"
                >
                    Mark as Completed
                </button>
                <button
                    @click="handleEdit"
                    class="w-full px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600"
                >
                    Edit Booking
                </button>
                <button
                    v-if="event.metadata?.status === 'confirmed'"
                    @click="handleCancel"
                    class="w-full px-4 py-2 text-sm font-medium text-red-600 bg-white border border-red-300 rounded-lg hover:bg-red-50 focus:ring-4 focus:ring-red-300 dark:bg-gray-700 dark:text-red-400 dark:border-red-600 dark:hover:bg-gray-600"
                >
                    Cancel Booking
                </button>
            </div>
        </div>
    </Transition>
</template>
