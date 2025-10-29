<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    modelValue: {
        type: Object,
        default: () => ({}),
    },
    filterOptions: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['update:modelValue', 'apply']);

const filters = ref({
    distance: props.modelValue.distance || 20,
    specialties: props.modelValue.specialties || [],
    lesson_types: props.modelValue.lesson_types || [],
    facilities: props.modelValue.facilities || [],
    technology: props.modelValue.technology || [],
});

const hasActiveFilters = computed(() => {
    return filters.value.specialties.length > 0 ||
           filters.value.lesson_types.length > 0 ||
           filters.value.facilities.length > 0 ||
           filters.value.technology.length > 0 ||
           filters.value.distance !== 20;
});

const toggleFilter = (category, value) => {
    const index = filters.value[category].indexOf(value);
    if (index > -1) {
        filters.value[category].splice(index, 1);
    } else {
        filters.value[category].push(value);
    }
};

const applyFilters = () => {
    emit('update:modelValue', filters.value);
    emit('apply', filters.value);
};

const clearFilters = () => {
    filters.value = {
        distance: 20,
        specialties: [],
        lesson_types: [],
        facilities: [],
        technology: [],
    };
    applyFilters();
};

const formatLabel = (value) => {
    return value.split('-').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
};
</script>

<template>
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 sticky top-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Filters</h3>
            <button
                v-if="hasActiveFilters"
                @click="clearFilters"
                class="text-sm text-blue-600 hover:text-blue-700 dark:text-blue-500 dark:hover:text-blue-400 font-medium"
            >
                Clear All
            </button>
        </div>

        <!-- Distance Slider -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Distance: {{ filters.distance }} miles
            </label>
            <input
                v-model.number="filters.distance"
                type="range"
                min="5"
                max="50"
                step="5"
                @change="applyFilters"
                class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700"
            />
            <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-1">
                <span>5 mi</span>
                <span>50 mi</span>
            </div>
        </div>

        <!-- Specialties -->
        <div class="mb-6">
            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Specialties</h4>
            <div class="space-y-2">
                <label
                    v-for="specialty in filterOptions.specialties"
                    :key="specialty"
                    class="flex items-center cursor-pointer"
                >
                    <input
                        type="checkbox"
                        :checked="filters.specialties.includes(specialty)"
                        @change="toggleFilter('specialties', specialty); applyFilters()"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                    />
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                        {{ formatLabel(specialty) }}
                    </span>
                </label>
            </div>
        </div>

        <!-- Lesson Types -->
        <div class="mb-6">
            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Lesson Types</h4>
            <div class="space-y-2">
                <label
                    v-for="type in filterOptions.lesson_types"
                    :key="type"
                    class="flex items-center cursor-pointer"
                >
                    <input
                        type="checkbox"
                        :checked="filters.lesson_types.includes(type)"
                        @change="toggleFilter('lesson_types', type); applyFilters()"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                    />
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                        {{ formatLabel(type) }}
                    </span>
                </label>
            </div>
        </div>

        <!-- Facilities -->
        <div class="mb-6">
            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Facilities</h4>
            <div class="space-y-2">
                <label
                    v-for="facility in filterOptions.facilities"
                    :key="facility"
                    class="flex items-center cursor-pointer"
                >
                    <input
                        type="checkbox"
                        :checked="filters.facilities.includes(facility)"
                        @change="toggleFilter('facilities', facility); applyFilters()"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                    />
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                        {{ formatLabel(facility) }}
                    </span>
                </label>
            </div>
        </div>

        <!-- Technology -->
        <div class="mb-6">
            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Technology</h4>
            <div class="space-y-2">
                <label
                    v-for="tech in filterOptions.technology"
                    :key="tech"
                    class="flex items-center cursor-pointer"
                >
                    <input
                        type="checkbox"
                        :checked="filters.technology.includes(tech)"
                        @change="toggleFilter('technology', tech); applyFilters()"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                    />
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                        {{ formatLabel(tech) }}
                    </span>
                </label>
            </div>
        </div>
    </div>
</template>
