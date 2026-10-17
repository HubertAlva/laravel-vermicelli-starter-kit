<script setup lang="ts">
import UserSidebarLayout from '@/layouts/user/UserSidebarLayout.vue';
import type { BreadcrumbItem } from '@/types';

const { breadcrumbs = [] } = defineProps<{
    breadcrumbs?: BreadcrumbItem[];
}>();
</script>

<template>
    <UserSidebarLayout :breadcrumbs="breadcrumbs">
        <slot />
    </UserSidebarLayout>
</template>
