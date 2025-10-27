<script setup>
import { computed, watch } from 'vue';
import CalendarEvent from './CalendarEvent.vue';

const props = defineProps({
    events: {
        type: Array,
        default: () => []
    },
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

const emit = defineEmits(['eventClick', 'dateClick']);

// Debug logging
watch(() => props.events, (newEvents) => {
    console.log('CalendarGrid received events:', newEvents?.length || 0);
    if (newEvents && newEvents.length > 0) {
        console.log('First event:', newEvents[0]);
    }
}, { immediate: true });

const weekDays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
const timeSlots = computed(() => {
    const slots = [];
    for (let hour = 8; hour <= 18; hour++) {
        slots.push(`${hour.toString().padStart(2, '0')}:00`);
    }
    return slots;
});

// Get the days to display based on view
const displayDays = computed(() => {
    const days = [];

    if (props.view === 'day') {
        days.push(new Date(props.currentDate));
    } else if (props.view === 'week') {
        const startOfWeek = new Date(props.currentDate);
        startOfWeek.setDate(startOfWeek.getDate() - startOfWeek.getDay());

        for (let i = 0; i < 7; i++) {
            const day = new Date(startOfWeek);
            day.setDate(day.getDate() + i);
            days.push(day);
        }
    } else if (props.view === 'month') {
        const year = props.currentDate.getFullYear();
        const month = props.currentDate.getMonth();
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);

        // Start from the Sunday before the first day of the month
        const startDate = new Date(firstDay);
        startDate.setDate(startDate.getDate() - startDate.getDay());

        // End on the Saturday after the last day of the month
        const endDate = new Date(lastDay);
        endDate.setDate(endDate.getDate() + (6 - endDate.getDay()));

        const current = new Date(startDate);
        while (current <= endDate) {
            days.push(new Date(current));
            current.setDate(current.getDate() + 1);
        }
    }

    return days;
});

// Get events for a specific day and optionally time slot
const getEventsFor = (date, timeSlot = null) => {
    const dateStr = date.toISOString().split('T')[0];

    return props.events.filter(event => {
        const eventDate = new Date(event.start).toISOString().split('T')[0];

        if (eventDate !== dateStr) return false;

        if (timeSlot && !event.allDay) {
            const eventStartTime = new Date(event.start).toTimeString().substring(0, 5);
            const slotHour = timeSlot.split(':')[0];
            const eventHour = eventStartTime.split(':')[0];
            return eventHour === slotHour;
        }

        return true;
    });
};

const isToday = (date) => {
    const today = new Date();
    return date.toDateString() === today.toDateString();
};

const isCurrentMonth = (date) => {
    return date.getMonth() === props.currentDate.getMonth();
};

const handleEventClick = (event) => {
    emit('eventClick', event);
};

const handleDateClick = (date) => {
    emit('dateClick', date);
};

// Split days into weeks for month view
const monthWeeks = computed(() => {
    if (props.view !== 'month') return [];

    const weeks = [];
    for (let i = 0; i < displayDays.value.length; i += 7) {
        weeks.push(displayDays.value.slice(i, i + 7));
    }
    return weeks;
});
</script>

<template>
    <div class="bg-white dark:bg-gray-800 overflow-auto">
        <!-- Month View -->
        <div v-if="view === 'month'" class="min-w-full">
            <!-- Day headers -->
            <div class="grid grid-cols-7 border-b border-gray-200 dark:border-gray-700">
                <div
                    v-for="day in weekDays"
                    :key="day"
                    class="p-2 text-center text-sm font-semibold text-gray-700 dark:text-gray-300"
                >
                    {{ day }}
                </div>
            </div>

            <!-- Calendar grid -->
            <div class="grid grid-cols-7">
                <div
                    v-for="(week, weekIndex) in monthWeeks"
                    :key="weekIndex"
                    class="contents"
                >
                    <div
                        v-for="(date, dayIndex) in week"
                        :key="`${weekIndex}-${dayIndex}`"
                        :class="[
                            'min-h-24 p-2 border-r border-b border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50',
                            !isCurrentMonth(date) && 'bg-gray-50 dark:bg-gray-900/50',
                            isToday(date) && 'bg-blue-50 dark:bg-blue-900/20'
                        ]"
                        @click="handleDateClick(date)"
                    >
                        <div
                            :class="[
                                'text-sm font-medium mb-1',
                                isToday(date) ? 'text-blue-600 dark:text-blue-400' : 'text-gray-900 dark:text-gray-100',
                                !isCurrentMonth(date) && 'text-gray-400 dark:text-gray-600'
                            ]"
                        >
                            {{ date.getDate() }}
                        </div>
                        <div class="space-y-1">
                            <CalendarEvent
                                v-for="event in getEventsFor(date).slice(0, 3)"
                                :key="event.id"
                                :event="event"
                                @click.stop="handleEventClick(event)"
                            />
                            <div
                                v-if="getEventsFor(date).length > 3"
                                class="text-xs text-gray-500 dark:text-gray-400 pl-2"
                            >
                                +{{ getEventsFor(date).length - 3 }} more
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Week/Day View -->
        <div v-else class="min-w-full">
            <!-- Day headers -->
            <div class="grid border-b border-gray-200 dark:border-gray-700 sticky top-0 bg-white dark:bg-gray-800 z-10" :style="{ gridTemplateColumns: `80px repeat(${displayDays.length}, 1fr)` }">
                <div class="p-2"></div>
                <div
                    v-for="date in displayDays"
                    :key="date.toISOString()"
                    :class="[
                        'p-2 text-center',
                        isToday(date) && 'bg-blue-50 dark:bg-blue-900/20'
                    ]"
                >
                    <div class="text-xs text-gray-600 dark:text-gray-400">
                        {{ weekDays[date.getDay()] }}
                    </div>
                    <div
                        :class="[
                            'text-lg font-semibold',
                            isToday(date) ? 'text-blue-600 dark:text-blue-400' : 'text-gray-900 dark:text-gray-100'
                        ]"
                    >
                        {{ date.getDate() }}
                    </div>
                </div>
            </div>

            <!-- Time slots -->
            <div v-for="timeSlot in timeSlots" :key="timeSlot" class="grid border-b border-gray-200 dark:border-gray-700" :style="{ gridTemplateColumns: `80px repeat(${displayDays.length}, 1fr)` }">
                <div class="p-2 text-xs text-gray-600 dark:text-gray-400 text-right pr-4">
                    {{ timeSlot }}
                </div>
                <div
                    v-for="date in displayDays"
                    :key="`${date.toISOString()}-${timeSlot}`"
                    :class="[
                        'p-2 border-l border-gray-200 dark:border-gray-700 min-h-16',
                        isToday(date) && 'bg-blue-50/30 dark:bg-blue-900/10'
                    ]"
                >
                    <div class="space-y-1">
                        <CalendarEvent
                            v-for="event in getEventsFor(date, timeSlot)"
                            :key="event.id"
                            :event="event"
                            @click="handleEventClick(event)"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
