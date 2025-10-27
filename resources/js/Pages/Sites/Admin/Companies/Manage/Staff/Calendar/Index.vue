<script setup>
import AdminLayout from '@/Layouts/Sites/Admin/Layout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, onMounted, watch } from 'vue';
import CalendarHeader from '@/Components/Calendar/CalendarHeader.vue';
import CalendarGrid from '@/Components/Calendar/CalendarGrid.vue';
import BookingDetailsPanel from '@/Components/Calendar/BookingDetailsPanel.vue';

const props = defineProps({
    company: Object,
    staff: Object,
});

const currentDate = ref(new Date());
const view = ref('week');
const events = ref([]);
const selectedEvent = ref(null);
const showDetailsPanel = ref(false);
const loading = ref(false);

// Fetch events when date or view changes
const fetchEvents = async () => {
    loading.value = true;
    try {
        // Calculate start and end dates based on current view
        let start, end;

        if (view.value === 'month') {
            start = new Date(currentDate.value.getFullYear(), currentDate.value.getMonth(), 1);
            end = new Date(currentDate.value.getFullYear(), currentDate.value.getMonth() + 1, 0);
        } else if (view.value === 'week') {
            start = new Date(currentDate.value);
            start.setDate(start.getDate() - start.getDay());
            end = new Date(start);
            end.setDate(end.getDate() + 6);
        } else {
            start = new Date(currentDate.value);
            end = new Date(currentDate.value);
        }

        const startStr = start.toISOString().split('T')[0];
        const endStr = end.toISOString().split('T')[0];

        const url = route('admin.companies.staff.calendar.events', {
            company: props.company.id,
            staff: props.staff.id,
        }) + `?start=${startStr}&end=${endStr}`;

        console.log('Fetching events from:', url);
        console.log('Date range:', startStr, 'to', endStr);

        const response = await fetch(url);

        const data = await response.json();
        console.log('Received events:', data.events?.length || 0, 'events');
        console.log('Sample event:', data.events?.[0]);
        events.value = data.events || [];
    } catch (error) {
        console.error('Error fetching calendar events:', error);
    } finally {
        loading.value = false;
    }
};

// Handle event click
const handleEventClick = (event) => {
    selectedEvent.value = event;
    showDetailsPanel.value = true;
};

// Handle date click (for month view)
const handleDateClick = (date) => {
    if (view.value === 'month') {
        currentDate.value = date;
        view.value = 'day';
    }
};

// Go to today
const goToToday = () => {
    currentDate.value = new Date();
    fetchEvents();
};

// Close details panel
const closeDetailsPanel = () => {
    showDetailsPanel.value = false;
    selectedEvent.value = null;
};

// Handle booking actions
const handleEditBooking = (event) => {
    // Navigate to booking edit page (to be implemented)
    console.log('Edit booking:', event);
    closeDetailsPanel();
};

const handleCancelBooking = (event) => {
    if (confirm('Are you sure you want to cancel this booking?')) {
        // TODO: Implement booking cancellation
        console.log('Cancel booking:', event);
        closeDetailsPanel();
        fetchEvents();
    }
};

const handleCompleteBooking = (event) => {
    if (confirm('Mark this booking as completed?')) {
        // TODO: Implement booking completion
        console.log('Complete booking:', event);
        closeDetailsPanel();
        fetchEvents();
    }
};

// Watch for date and view changes
watch([currentDate, view], () => {
    fetchEvents();
});

// Initial fetch
onMounted(() => {
    fetchEvents();
});
</script>

<template>
    <Head :title="`Calendar - ${staff.first_name} ${staff.last_name}`" />

    <AdminLayout>
        <!-- Breadcrumb -->
        <div class="p-4 bg-white block sm:flex items-center justify-between border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <div class="w-full mb-1">
                <nav class="flex mb-5" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2">
                        <li class="inline-flex items-center">
                            <Link :href="route('admin.companies.index')" class="text-gray-700 hover:text-blue-600 dark:text-gray-300 dark:hover:text-white">
                                Companies
                            </Link>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <Link :href="route('admin.companies.staff.index', company.id)" class="ml-1 text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-300 dark:hover:text-white">
                                    {{ company.name }}
                                </Link>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500">{{ staff.first_name }} {{ staff.last_name }}</span>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="ml-1 text-gray-500 md:ml-2 dark:text-gray-400">Calendar</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">
                    Calendar - {{ staff.first_name }} {{ staff.last_name }}
                </h1>
            </div>
        </div>

        <!-- Legend -->
        <div class="p-4 bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex flex-wrap gap-4 items-center">
                <div class="flex items-center">
                    <div class="w-4 h-4 mr-2 rounded bg-green-100 border border-green-300 dark:bg-green-900/30 dark:border-green-700"></div>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Available Slots</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 mr-2 rounded bg-blue-100 border border-blue-300 dark:bg-blue-900/30 dark:border-blue-700"></div>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Booked</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 mr-2 rounded bg-red-100 border border-red-300 dark:bg-red-900/30 dark:border-red-700"></div>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Unavailable / Exceptions</span>
                </div>
                <div class="ml-auto flex gap-2">
                    <Link
                        :href="route('admin.companies.staff.availability.index', [company.id, staff.id])"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                    >
                        Manage Availability
                    </Link>
                    <Link
                        :href="route('admin.companies.staff.exceptions.index', [company.id, staff.id])"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                    >
                        Manage Exceptions
                    </Link>
                </div>
            </div>
        </div>

        <!-- Calendar Header -->
        <CalendarHeader
            :current-date="currentDate"
            :view="view"
            @update:currentDate="currentDate = $event"
            @update:view="view = $event"
            @today="goToToday"
        />

        <!-- Loading State -->
        <div v-if="loading" class="flex items-center justify-center p-12 bg-white dark:bg-gray-800">
            <div class="text-gray-500 dark:text-gray-400">Loading calendar...</div>
        </div>

        <!-- Calendar Grid -->
        <CalendarGrid
            v-else
            :events="events"
            :current-date="currentDate"
            :view="view"
            @event-click="handleEventClick"
            @date-click="handleDateClick"
        />

        <!-- Booking Details Panel -->
        <BookingDetailsPanel
            :event="selectedEvent"
            :show="showDetailsPanel"
            @close="closeDetailsPanel"
            @edit="handleEditBooking"
            @cancel="handleCancelBooking"
            @complete="handleCompleteBooking"
        />
    </AdminLayout>
</template>
