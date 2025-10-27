<script setup>
import PublicLayout from '@/Layouts/Sites/Public/Layout.vue';
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
    customer_name: '',
    customer_email: '',
    customer_phone: '',
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
                'Accept': 'application/json',
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

            // Handle Laravel validation errors (422 status)
            if (response.status === 422 && data.errors) {
                // Laravel validation errors format: { errors: { field: [messages] } }
                errors.value = {};
                Object.keys(data.errors).forEach(key => {
                    errors.value[key] = data.errors[key][0]; // Get first error message
                });
                // Also set general error with main message
                errors.value.general = data.message || 'Please check the form for errors.';

                // Scroll back to step 2 if there are customer form field errors
                const customerFields = ['customer_name', 'customer_email', 'customer_phone', 'notes'];
                const hasCustomerFieldError = Object.keys(data.errors).some(key => customerFields.includes(key));
                if (hasCustomerFieldError && step.value === 3) {
                    step.value = 2;
                }
            } else {
                // Other error responses
                errors.value = { general: data.message || 'Failed to create booking' };
            }
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
    <Head title="Book Coaching Session" />

    <PublicLayout>
        <!-- Breadcrumb -->
        <section class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <Link :href="route('home')" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                                </svg>
                                Home
                            </Link>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <Link :href="route('companies.show', company.id)" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">
                                    {{ company.name }}
                                </Link>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Book Session</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
        </section>

        <section class="bg-gray-50 dark:bg-gray-900 py-8">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Booking Header -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6 mb-6">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Book Your Coaching Session</h1>
                    <div class="flex items-center">
                        <div class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white text-lg font-bold flex-shrink-0">
                            {{ staff.first_name.charAt(0) }}{{ staff.last_name.charAt(0) }}
                        </div>
                        <div class="ml-4">
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ staff.first_name }} {{ staff.last_name }}
                            </p>
                            <p v-if="staff.position" class="text-sm text-gray-600 dark:text-gray-400">{{ staff.position }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ company.name }}</p>
                        </div>
                    </div>
                </div>

                <!-- Step Indicators -->
                <div class="mb-8">
                    <div class="flex items-center justify-center">
                        <div :class="['flex items-center', step >= 1 ? 'text-blue-600 dark:text-blue-500' : 'text-gray-400 dark:text-gray-500']">
                            <div :class="['w-10 h-10 rounded-full flex items-center justify-center font-semibold', step >= 1 ? 'bg-blue-600 text-white dark:bg-blue-600' : 'bg-gray-300 text-gray-600 dark:bg-gray-600 dark:text-gray-300']">
                                1
                            </div>
                            <span class="ml-2 hidden sm:inline font-medium">Select Time</span>
                        </div>
                        <div class="w-16 h-1 mx-2 rounded" :class="step >= 2 ? 'bg-blue-600 dark:bg-blue-600' : 'bg-gray-300 dark:bg-gray-600'"></div>
                        <div :class="['flex items-center', step >= 2 ? 'text-blue-600 dark:text-blue-500' : 'text-gray-400 dark:text-gray-500']">
                            <div :class="['w-10 h-10 rounded-full flex items-center justify-center font-semibold', step >= 2 ? 'bg-blue-600 text-white dark:bg-blue-600' : 'bg-gray-300 text-gray-600 dark:bg-gray-600 dark:text-gray-300']">
                                2
                            </div>
                            <span class="ml-2 hidden sm:inline font-medium">Your Details</span>
                        </div>
                        <div class="w-16 h-1 mx-2 rounded" :class="step >= 3 ? 'bg-blue-600 dark:bg-blue-600' : 'bg-gray-300 dark:bg-gray-600'"></div>
                        <div :class="['flex items-center', step >= 3 ? 'text-blue-600 dark:text-blue-500' : 'text-gray-400 dark:text-gray-500']">
                            <div :class="['w-10 h-10 rounded-full flex items-center justify-center font-semibold', step >= 3 ? 'bg-blue-600 text-white dark:bg-blue-600' : 'bg-gray-300 text-gray-600 dark:bg-gray-600 dark:text-gray-300']">
                                3
                            </div>
                            <span class="ml-2 hidden sm:inline font-medium">Review</span>
                        </div>
                    </div>
                </div>

                <!-- Step 1: Select Date & Time -->
                <div v-show="step === 1" class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Select Date & Time</h2>

                    <!-- Date Selection -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Select a date</label>
                        <select
                            v-model="selectedDate"
                            class="w-full px-4 py-3 text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-4 focus:ring-blue-300 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        >
                            <option :value="null">Choose a date...</option>
                            <option v-for="date in availableDates" :key="date.toISOString()" :value="date.toISOString().split('T')[0]">
                                {{ formatDate(date) }}
                            </option>
                        </select>
                    </div>

                    <!-- Time Slot Selection -->
                    <div v-if="selectedDate">
                        <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Select a time</label>

                        <div v-if="loadingSlots" class="text-center py-12">
                            <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 dark:border-blue-500"></div>
                            <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">Loading available times...</p>
                        </div>

                        <div v-else-if="slotsForSelectedDate.length > 0" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                            <button
                                v-for="slot in slotsForSelectedDate"
                                :key="slot.start_time"
                                @click="selectSlot(slot)"
                                :class="[
                                    'px-4 py-3 rounded-lg border-2 text-sm font-medium transition-colors',
                                    selectedSlot?.start_time === slot.start_time
                                        ? 'border-blue-600 bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:border-blue-500 dark:text-blue-400'
                                        : 'border-gray-300 dark:border-gray-600 hover:border-blue-400 dark:hover:border-blue-500 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700'
                                ]"
                            >
                                {{ slot.start_time.substring(0, 5) }}
                            </button>
                        </div>

                        <div v-else class="text-center py-12 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">No available times for this date</p>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button
                            @click="goToStep(2)"
                            :disabled="!selectedDate || !selectedSlot"
                            :class="[
                                'px-6 py-3 rounded-lg font-medium focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800',
                                selectedDate && selectedSlot
                                    ? 'bg-blue-700 text-white hover:bg-blue-800 dark:bg-blue-600 dark:hover:bg-blue-700'
                                    : 'bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed'
                            ]"
                        >
                            Continue
                        </button>
                    </div>
                </div>

                <!-- Step 2: Customer Details -->
                <div v-show="step === 2" class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Your Details</h2>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Full Name *</label>
                            <input
                                v-model="customerForm.customer_name"
                                type="text"
                                required
                                :class="[
                                    'w-full px-4 py-3 text-gray-900 bg-white rounded-lg focus:ring-4 dark:bg-gray-700 dark:text-white',
                                    errors.customer_name
                                        ? 'border-2 border-red-500 focus:border-red-500 focus:ring-red-300 dark:border-red-500 dark:focus:ring-red-800'
                                        : 'border border-gray-300 focus:ring-blue-300 focus:border-blue-500 dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500'
                                ]"
                                placeholder="Enter your full name"
                            />
                            <p v-if="errors.customer_name" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.customer_name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Email Address *</label>
                            <input
                                v-model="customerForm.customer_email"
                                type="email"
                                required
                                :class="[
                                    'w-full px-4 py-3 text-gray-900 bg-white rounded-lg focus:ring-4 dark:bg-gray-700 dark:text-white',
                                    errors.customer_email
                                        ? 'border-2 border-red-500 focus:border-red-500 focus:ring-red-300 dark:border-red-500 dark:focus:ring-red-800'
                                        : 'border border-gray-300 focus:ring-blue-300 focus:border-blue-500 dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500'
                                ]"
                                placeholder="your.email@example.com"
                            />
                            <p v-if="errors.customer_email" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.customer_email }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Phone Number</label>
                            <input
                                v-model="customerForm.customer_phone"
                                type="tel"
                                :class="[
                                    'w-full px-4 py-3 text-gray-900 bg-white rounded-lg focus:ring-4 dark:bg-gray-700 dark:text-white',
                                    errors.customer_phone
                                        ? 'border-2 border-red-500 focus:border-red-500 focus:ring-red-300 dark:border-red-500 dark:focus:ring-red-800'
                                        : 'border border-gray-300 focus:ring-blue-300 focus:border-blue-500 dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500'
                                ]"
                                placeholder="+44 7700 900123"
                            />
                            <p v-if="errors.customer_phone" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.customer_phone }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Notes (Optional)</label>
                            <textarea
                                v-model="customerForm.notes"
                                rows="4"
                                :class="[
                                    'w-full px-4 py-3 text-gray-900 bg-white rounded-lg focus:ring-4 dark:bg-gray-700 dark:text-white',
                                    errors.notes
                                        ? 'border-2 border-red-500 focus:border-red-500 focus:ring-red-300 dark:border-red-500 dark:focus:ring-red-800'
                                        : 'border border-gray-300 focus:ring-blue-300 focus:border-blue-500 dark:border-gray-600 dark:focus:ring-blue-500 dark:focus:border-blue-500'
                                ]"
                                placeholder="Any special requests or areas you'd like to focus on..."
                            ></textarea>
                            <p v-if="errors.notes" class="mt-1 text-sm text-red-600 dark:text-red-400">{{ errors.notes }}</p>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-between">
                        <button
                            @click="goToStep(1)"
                            class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700"
                        >
                            Back
                        </button>
                        <button
                            @click="goToStep(3)"
                            :disabled="!customerForm.customer_name || !customerForm.customer_email"
                            :class="[
                                'px-6 py-3 rounded-lg font-medium focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800',
                                customerForm.customer_name && customerForm.customer_email
                                    ? 'bg-blue-700 text-white hover:bg-blue-800 dark:bg-blue-600 dark:hover:bg-blue-700'
                                    : 'bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 cursor-not-allowed'
                            ]"
                        >
                            Review Booking
                        </button>
                    </div>
                </div>

                <!-- Step 3: Review & Confirm -->
                <div v-show="step === 3" class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Review Your Booking</h2>

                    <div class="space-y-5 mb-6">
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Session Details</h3>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ formatDate(selectedDate) }}</p>
                            <p class="text-gray-700 dark:text-gray-300">{{ selectedSlot?.start_time.substring(0, 5) }} - {{ selectedSlot?.end_time.substring(0, 5) }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Duration: {{ selectedSlot?.duration }} minutes</p>
                        </div>

                        <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Coach</h3>
                            <p class="text-gray-900 dark:text-white font-medium">{{ staff.first_name }} {{ staff.last_name }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ company.name }}</p>
                        </div>

                        <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Your Information</h3>
                            <p class="text-gray-900 dark:text-white">{{ customerForm.customer_name }}</p>
                            <p class="text-gray-700 dark:text-gray-300">{{ customerForm.customer_email }}</p>
                            <p v-if="customerForm.customer_phone" class="text-gray-700 dark:text-gray-300">{{ customerForm.customer_phone }}</p>
                        </div>

                        <div v-if="customerForm.notes" class="border-b border-gray-200 dark:border-gray-700 pb-4">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Notes</h3>
                            <p class="text-gray-700 dark:text-gray-300">{{ customerForm.notes }}</p>
                        </div>

                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-5 border border-blue-100 dark:border-blue-800">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-semibold text-gray-900 dark:text-white">Total:</span>
                                <span class="text-3xl font-bold text-blue-600 dark:text-blue-500">$50.00</span>
                            </div>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-2">Payment will be processed securely via Stripe</p>
                        </div>
                    </div>

                    <div v-if="errors.general" class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                        <p class="text-sm text-red-800 dark:text-red-400">{{ errors.general }}</p>
                    </div>

                    <div class="flex justify-between">
                        <button
                            @click="goToStep(2)"
                            class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700"
                        >
                            Back
                        </button>
                        <button
                            @click="submitBooking"
                            :disabled="submitting"
                            class="px-8 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 disabled:bg-gray-400 dark:disabled:bg-gray-600 disabled:cursor-not-allowed focus:ring-4 focus:ring-green-300 dark:focus:ring-green-800 dark:bg-green-700 dark:hover:bg-green-800"
                        >
                            {{ submitting ? 'Processing...' : 'Proceed to Payment' }}
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
