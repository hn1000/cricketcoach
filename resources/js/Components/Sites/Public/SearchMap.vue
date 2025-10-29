<script setup>
import { ref, onMounted, watch } from 'vue';
import { Loader } from '@googlemaps/js-api-loader';

const props = defineProps({
    companies: {
        type: Array,
        default: () => [],
    },
    userCoordinates: {
        type: Object,
        default: null,
    },
    distance: {
        type: Number,
        default: 20,
    },
});

const emit = defineEmits(['company-selected']);

const mapContainer = ref(null);
const map = ref(null);
const markers = ref([]);
const circle = ref(null);
const isLoading = ref(true);
const error = ref(null);

const API_KEY = import.meta.env.VITE_GOOGLE_MAPS_API_KEY;

const initMap = async () => {
    if (!API_KEY) {
        error.value = 'Google Maps API key not configured';
        isLoading.value = false;
        return;
    }

    try {
        const loader = new Loader({
            apiKey: API_KEY,
            version: 'weekly',
        });

        const { Map } = await loader.importLibrary('maps');
        const { AdvancedMarkerElement, PinElement } = await loader.importLibrary('marker');

        // Default center (UK)
        const center = props.userCoordinates
            ? { lat: props.userCoordinates.latitude, lng: props.userCoordinates.longitude }
            : { lat: 52.4862, lng: -1.8904 }; // Birmingham, UK

        map.value = new Map(mapContainer.value, {
            center,
            zoom: props.userCoordinates ? 10 : 6,
            mapId: 'CRICKET_COACH_MAP',
        });

        // Add radius circle if user location is available
        if (props.userCoordinates) {
            addRadiusCircle();
        }

        // Add markers for companies
        addMarkers();

        isLoading.value = false;
    } catch (err) {
        error.value = `Error loading map: ${err.message}`;
        isLoading.value = false;
    }
};

const addRadiusCircle = async () => {
    if (!props.userCoordinates || !map.value) return;

    const { Circle } = await google.maps.importLibrary('maps');

    // Remove existing circle
    if (circle.value) {
        circle.value.setMap(null);
    }

    circle.value = new Circle({
        strokeColor: '#22c55e',
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: '#22c55e',
        fillOpacity: 0.15,
        map: map.value,
        center: {
            lat: props.userCoordinates.latitude,
            lng: props.userCoordinates.longitude,
        },
        radius: props.distance * 1609.34, // Convert miles to meters
    });
};

const addMarkers = async () => {
    if (!map.value) return;

    // Clear existing markers
    markers.value.forEach(marker => marker.setMap(null));
    markers.value = [];

    const { AdvancedMarkerElement, PinElement } = await google.maps.importLibrary('marker');
    const { InfoWindow } = await google.maps.importLibrary('maps');

    props.companies.forEach((company, index) => {
        if (!company.latitude || !company.longitude) return;

        const pinElement = new PinElement({
            background: '#22c55e',
            borderColor: '#166534',
            glyphColor: '#fff',
        });

        const marker = new AdvancedMarkerElement({
            map: map.value,
            position: { lat: company.latitude, lng: company.longitude },
            title: company.name,
            content: pinElement.element,
        });

        const infoWindow = new InfoWindow({
            content: `
                <div class="p-2">
                    <h3 class="font-bold text-gray-900">${company.name}</h3>
                    <p class="text-sm text-gray-600">${company.address || ''}</p>
                    ${company.distance !== undefined ? `<p class="text-sm text-green-600 font-semibold mt-1">${company.distance} miles away</p>` : ''}
                </div>
            `,
        });

        marker.addListener('click', () => {
            infoWindow.open(map.value, marker);
            emit('company-selected', company);
        });

        markers.value.push(marker);
    });

    // Fit bounds to show all markers
    if (markers.value.length > 0) {
        const bounds = new google.maps.LatLngBounds();

        if (props.userCoordinates) {
            bounds.extend({
                lat: props.userCoordinates.latitude,
                lng: props.userCoordinates.longitude,
            });
        }

        props.companies.forEach(company => {
            if (company.latitude && company.longitude) {
                bounds.extend({ lat: company.latitude, lng: company.longitude });
            }
        });

        map.value.fitBounds(bounds);
    }
};

onMounted(() => {
    initMap();
});

watch(() => props.companies, () => {
    if (map.value) {
        addMarkers();
    }
}, { deep: true });

watch(() => [props.userCoordinates, props.distance], () => {
    if (map.value && props.userCoordinates) {
        addRadiusCircle();
    }
}, { deep: true });
</script>

<template>
    <div class="relative w-full h-full">
        <div
            v-if="isLoading"
            class="absolute inset-0 flex items-center justify-center bg-gray-100 dark:bg-gray-800 rounded-lg"
        >
            <div class="text-center">
                <svg class="animate-spin h-12 w-12 text-green-600 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-gray-600 dark:text-gray-400">Loading map...</p>
            </div>
        </div>

        <div
            v-else-if="error"
            class="absolute inset-0 flex items-center justify-center bg-gray-100 dark:bg-gray-800 rounded-lg"
        >
            <div class="text-center p-6">
                <svg class="w-16 h-16 text-red-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <p class="text-red-600 dark:text-red-400 font-medium">{{ error }}</p>
                <p class="text-gray-600 dark:text-gray-400 text-sm mt-2">Please add VITE_GOOGLE_MAPS_API_KEY to your .env file</p>
            </div>
        </div>

        <div
            ref="mapContainer"
            class="w-full h-full rounded-lg"
            :class="{ 'opacity-0': isLoading || error }"
        ></div>
    </div>
</template>
