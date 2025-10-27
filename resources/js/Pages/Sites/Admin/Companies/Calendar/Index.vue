<script setup>
import AdminLayout from '@/Layouts/Sites/Admin/Layout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, watch, computed } from 'vue';
import CalendarHeader from '@/Components/Calendar/CalendarHeader.vue';
import CalendarGrid from '@/Components/Calendar/CalendarGrid.vue';
import BookingDetailsPanel from '@/Components/Calendar/BookingDetailsPanel.vue';
import Tabs from '../Manage/Tabs.vue';

const props = defineProps({
    company: Object,
    staffMembers: Array,
});

const currentDate = ref(new Date());
const view = ref('week');
const events = ref([]);
const selectedEvent = ref(null);
const showDetailsPanel = ref(false);
const loading = ref(false);
const selectedStaffIds = ref([]);
const staffColors = ref({});

// Staff filter options
const staffOptions = computed(() => {
    return props.staffMembers.map(staff => ({
        id: staff.id,
        name: `${staff.first_name} ${staff.last_name}`,
        selected: selectedStaffIds.value.includes(staff.id)
    }));
});

// Toggle all staff
const allStaffSelected = computed(() => {
    return selectedStaffIds.value.length === props.staffMembers.length;
});

const toggleAllStaff = () => {
    if (allStaffSelected.value) {
        selectedStaffIds.value = [];
    } else {
        selectedStaffIds.value = props.staffMembers.map(s => s.id);
    }
};

// Toggle individual staff
const toggleStaff = (staffId) => {
    const index = selectedStaffIds.value.indexOf(staffId);
    if (index > -1) {
        selectedStaffIds.value.splice(index, 1);
    } else {
        selectedStaffIds.value.push(staffId);
    }
};

// Fetch bookings when date, view, or staff selection changes
const fetchBookings = async () => {
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

        const params = new URLSearchParams({
            start: startStr,
            end: endStr,
        });

        // Add staff IDs if any are selected
        if (selectedStaffIds.value.length > 0) {
            selectedStaffIds.value.forEach(id => {
                params.append('staff_ids[]', id);
            });
        }

        const response = await fetch(
            route('admin.companies.calendar.bookings', {
                company: props.company.id,
            }) + `?${params}`
        );

        const data = await response.json();
        events.value = data.events || [];
        staffColors.value = data.staffColors || {};
    } catch (error) {
        console.error('Error fetching calendar bookings:', error);
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
    fetchBookings();
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
        fetchBookings();
    }
};

const handleCompleteBooking = (event) => {
    if (confirm('Mark this booking as completed?')) {
        // TODO: Implement booking completion
        console.log('Complete booking:', event);
        closeDetailsPanel();
        fetchBookings();
    }
};

// Watch for date, view, and staff selection changes
watch([currentDate, view, selectedStaffIds], () => {
    fetchBookings();
}, { deep: true });

// Initial fetch - show all staff by default
onMounted(() => {
    selectedStaffIds.value = props.staffMembers.map(s => s.id);
    fetchBookings();
});
</script>

<template>
    <Head :title="`Calendar - ${company.name}`" />

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
                                <span class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500">{{ company.name }}</span>
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
                    {{ company.name }}
                </h1>
            </div>
        </div>

        <!-- Tabs -->
        <div class="bg-white dark:bg-gray-800">
            <Tabs :company="company" active-tab="calendar" />
        </div>

        <!-- Staff Filter -->
        <div class="p-4 bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex items-center">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mr-3">Filter by Staff:</label>
                    <button
                        @click="toggleAllStaff"
                        :class="[
                            'px-3 py-1.5 text-sm font-medium rounded-lg border',
                            allStaffSelected
                                ? 'bg-blue-600 text-white border-blue-600'
                                : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600'
                        ]"
                    >
                        All Staff
                    </button>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button
                        v-for="staff in staffOptions"
                        :key="staff.id"
                        @click="toggleStaff(staff.id)"
                        :class="[
                            'px-3 py-1.5 text-sm font-medium rounded-lg border transition-colors',
                            staff.selected
                                ? 'border-blue-600 text-white'
                                : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600'
                        ]"
                        :style="staff.selected && staffColors[staff.id] ? {
                            backgroundColor: staffColors[staff.id].border,
                            borderColor: staffColors[staff.id].border
                        } : {}"
                    >
                        {{ staff.name }}
                    </button>
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
