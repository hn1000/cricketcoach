<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({
    company: Object,
});

// Redirect to the staff index page
router.visit(route('admin.companies.staff.index', props.company.id));
</script>

<template>
    <!-- This component redirects to the staff index page -->
</template>
