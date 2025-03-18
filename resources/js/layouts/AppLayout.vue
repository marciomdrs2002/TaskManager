<script setup lang="ts">
import AppLayout from '@/layouts/app/AppHeaderLayout.vue'; 
import type { BreadcrumbItemType } from '@/types';
import Toast from 'primevue/toast';
import { Head } from '@inertiajs/vue3';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
    headerTitle?: string;
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Toast />
        <Head :title="headerTitle" />
        <slot />
    </AppLayout>
</template>
