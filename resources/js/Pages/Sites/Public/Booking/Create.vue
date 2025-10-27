<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed, watch, onMounted } from 'vue';

const props = defineProps({
    company: Object,
    staff: Object,
    preselected: Object,
});

const step = ref(1); // 1: Select Date/Time, 2: Enter Details, 3: Review

// Form data
const selectedDate = ref(props.preselected?.date || null);
const selectedSlot = ref(null);
const availableSlots = ref({});
const loadingSlots = ref(false);

const customerForm = ref({
    customer_name: 'John Doe',
    customer_email: 'john.doe@example.com',
    customer_phone: '555-1234',
    notes: '',
});

const errors = ref({});
const submitting = ref(false);

// Calculate date range (next 30 days)
const availableDates = computed(() => {
    const dates = [];
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    for (let i = 0; i < 30; i++) {
        const date = new Date(today);
        date.setDate(date.getDate() + i);
        dates.push(date);
    }

    return dates;
});

// Fetch available slots when date is selected
watch(selectedDate, async (newDate) => {
    if (!newDate) return;

    selectedSlot.value = null;
    loadingSlots.value = true;

    try {
        // Convert date to Y-m-d format for API
        const formatDateForApi = (date) => {
            if (typeof date === 'string') {
                // Already a string, parse it first
                date = new Date(date);
            }
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        };

        const startDate = formatDateForApi(newDate);
        const endDate = formatDateForApi(newDate);

        const response = await fetch(
            route('api.companies.staff.availability', {
                company: props.company.id,
                staff: props.staff.id,
            }) + `?start_date=${startDate}&end_date=${endDate}`
        );

        const data = await response.json();
        availableSlots.value = data.slots || {};
    } catch (error) {
        console.error('Error fetching availability:', error);
    } finally {
        loadingSlots.value = false;
    }
});

// Helper to format date for consistency
const formatDateKey = (date) => {
    if (!date) return null;
    if (typeof date === 'string') {
        date = new Date(date);
    }
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
};

// Get slots for selected date
const slotsForSelectedDate = computed(() => {
    if (!selectedDate.value) {
        return [];
    }
    const dateKey = formatDateKey(selectedDate.value);
    return availableSlots.value[dateKey] || [];
});

const selectSlot = (slot) => {
    selectedSlot.value = slot;
};

const goToStep = (stepNumber) => {
    if (stepNumber === 2 && (!selectedDate.value || !selectedSlot.value)) {
        return; // Can't proceed without date/slot
    }
    step.value = stepNumber;
};

const submitBooking = async () => {
    if (submitting.value) return;

    errors.value = {};
    submitting.value = true;



    try {
        const response = await fetch(route('api.bookings.store'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
               // 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                company_id: props.company.id,
                staff_id: props.staff.id,
                booking_date: formatDateKey(selectedDate.value),
                start_time: selectedSlot.value.start_time,
                end_time: selectedSlot.value.end_time,
                ...customerForm.value,
            }),
        });

        const data = await response.json();

        if (response.ok) {
            // Redirect to checkout
            router.visit(route('checkout.show', data.order_id));
        } else {
            console.error('Booking creation failed:', response.status, data);
            errors.value = { general: data.message || 'Failed to create booking' };
        }
    } catch (error) {
        console.error('Booking creation error:', error);
        errors.value = { general: 'An error occurred. Please try again.' };
    } finally {
        submitting.value = false;
    }
};

// Format date for display
const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};

// Load initial slots if date is preselected
onMounted(() => {
    if (props.preselected?.date) {
        selectedDate.value = props.preselected.date;
    }
});
</script>

<template>
    <Head title="Book Appointment" />

    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <Link :href="route('companies.show', company.id)" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to {{ company.name }}
                </Link>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Booking Header -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Book Appointment</h1>
                <div class="mt-4 flex items-center">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white text-lg font-bold">
                        {{ staff.first_name.charAt(0) }}{{ staff.last_name.charAt(0) }}
                    </div>
                    <div class="ml-4">
                        <p class="text-lg font-semibold text-gray-900">
                            {{ staff.first_name }} {{ staff.last_name }}
                        </p>
                        <p v-if="staff.position" class="text-sm text-gray-600">{{ staff.position }}</p>
                    </div>
                </div>
            </div>

            <!-- Step Indicators -->
            <div class="mb-8">
                <div class="flex items-center justify-center">
                    <div :class="['flex items-center', step >= 1 ? 'text-blue-600' : 'text-gray-400']">
                        <div :class="['w-8 h-8 rounded-full flex items-center justify-center font-semibold', step >= 1 ? 'bg-blue-600 text-white' : 'bg-gray-300']">
                            1
                        </div>
                        <span class="ml-2 hidden sm:inline">Select Time</span>
                    </div>
                    <div class="w-16 h-0.5 mx-2" :class="step >= 2 ? 'bg-blue-600' : 'bg-gray-300'"></div>
                    <div :class="['flex items-center', step >= 2 ? 'text-blue-600' : 'text-gray-400']">
                        <div :class="['w-8 h-8 rounded-full flex items-center justify-center font-semibold', step >= 2 ? 'bg-blue-600 text-white' : 'bg-gray-300']">
                            2
                        </div>
                        <span class="ml-2 hidden sm:inline">Your Details</span>
                    </div>
                    <div class="w-16 h-0.5 mx-2" :class="step >= 3 ? 'bg-blue-600' : 'bg-gray-300'"></div>
                    <div :class="['flex items-center', step >= 3 ? 'text-blue-600' : 'text-gray-400']">
                        <div :class="['w-8 h-8 rounded-full flex items-center justify-center font-semibold', step >= 3 ? 'bg-blue-600 text-white' : 'bg-gray-300']">
                            3
                        </div>
                        <span class="ml-2 hidden sm:inline">Review</span>
                    </div>
                </div>
            </div>

            <!-- Step 1: Select Date & Time -->
            <div v-show="step === 1" class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Select Date & Time</h2>

                <!-- Date Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select a date</label>
                    <select
                        v-model="selectedDate"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option :value="null">Choose a date...</option>
                        <option v-for="date in availableDates" :key="date.toISOString()" :value="date.toISOString().split('T')[0]">
                            {{ formatDate(date) }}
                        </option>
                    </select>
                </div>

                <!-- Time Slot Selection -->
                <div v-if="selectedDate">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select a time</label>

                    <div v-if="loadingSlots" class="text-center py-8">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                        <p class="mt-2 text-sm text-gray-600">Loading available times...</p>
                    </div>

                    <div v-else-if="slotsForSelectedDate.length > 0" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                        <button
                            v-for="slot in slotsForSelectedDate"
                            :key="slot.start_time"
                            @click="selectSlot(slot)"
                            :class="[
                                'px-4 py-3 rounded-lg border-2 text-sm font-medium transition-colors',
                                selectedSlot?.start_time === slot.start_time
                                    ? 'border-blue-600 bg-blue-50 text-blue-700'
                                    : 'border-gray-300 hover:border-blue-400 text-gray-700'
                            ]"
                        >
                            {{ slot.start_time.substring(0, 5) }}
                        </button>
                    </div>

                    <div v-else class="text-center py-8 bg-gray-50 rounded-lg">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-600">No available times for this date</p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button
                        @click="goToStep(2)"
                        :disabled="!selectedDate || !selectedSlot"
                        :class="[
                            'px-6 py-2 rounded-lg font-medium',
                            selectedDate && selectedSlot
                                ? 'bg-blue-600 text-white hover:bg-blue-700'
                                : 'bg-gray-300 text-gray-500 cursor-not-allowed'
                        ]"
                    >
                        Continue
                    </button>
                </div>
            </div>

            <!-- Step 2: Customer Details -->
            <div v-show="step === 2" class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Your Details</h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                        <input
                            v-model="customerForm.customer_name"
                            type="text"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="John Doe"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                        <input
                            v-model="customerForm.customer_email"
                            type="email"
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="john@example.com"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input
                            v-model="customerForm.customer_phone"
                            type="tel"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="(555) 123-4567"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                        <textarea
                            v-model="customerForm.notes"
                            rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Any special requests or information..."
                        ></textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-between">
                    <button
                        @click="goToStep(1)"
                        class="px-6 py-2 border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Back
                    </button>
                    <button
                        @click="goToStep(3)"
                        :disabled="!customerForm.customer_name || !customerForm.customer_email"
                        :class="[
                            'px-6 py-2 rounded-lg font-medium',
                            customerForm.customer_name && customerForm.customer_email
                                ? 'bg-blue-600 text-white hover:bg-blue-700'
                                : 'bg-gray-300 text-gray-500 cursor-not-allowed'
                        ]"
                    >
                        Review Booking
                    </button>
                </div>
            </div>

            <!-- Step 3: Review & Confirm -->
            <div v-show="step === 3" class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Review Your Booking</h2>

                <div class="space-y-4 mb-6">
                    <div class="border-b border-gray-200 pb-4">
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Appointment Details</h3>
                        <p class="text-lg font-semibold text-gray-900">{{ formatDate(selectedDate) }}</p>
                        <p class="text-gray-700">{{ selectedSlot?.start_time.substring(0, 5) }} - {{ selectedSlot?.end_time.substring(0, 5) }}</p>
                        <p class="text-sm text-gray-600">Duration: {{ selectedSlot?.duration }} minutes</p>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Service Provider</h3>
                        <p class="text-gray-900">{{ staff.first_name }} {{ staff.last_name }}</p>
                        <p class="text-sm text-gray-600">{{ company.name }}</p>
                    </div>

                    <div class="border-b border-gray-200 pb-4">
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Your Information</h3>
                        <p class="text-gray-900">{{ customerForm.customer_name }}</p>
                        <p class="text-gray-700">{{ customerForm.customer_email }}</p>
                        <p v-if="customerForm.customer_phone" class="text-gray-700">{{ customerForm.customer_phone }}</p>
                    </div>

                    <div v-if="customerForm.notes" class="pb-4">
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Notes</h3>
                        <p class="text-gray-700">{{ customerForm.notes }}</p>
                    </div>

                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-gray-900">Total:</span>
                            <span class="text-2xl font-bold text-blue-600">$50.00</span>
                        </div>
                    </div>
                </div>

                <div v-if="errors.general" class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-sm text-red-800">{{ errors.general }}</p>
                </div>

                <div class="flex justify-between">
                    <button
                        @click="goToStep(2)"
                        class="px-6 py-2 border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Back
                    </button>
                    <button
                        @click="submitBooking"
                        :disabled="submitting"
                        class="px-6 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 disabled:bg-gray-400 disabled:cursor-not-allowed"
                    >
                        {{ submitting ? 'Processing...' : 'Proceed to Payment' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
