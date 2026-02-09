<script setup lang="ts">
import UserSidebarLayout from '@/layouts/user/UserSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <UserSidebarLayout :breadcrumbs="breadcrumbs">
        <slot />
    </UserSidebarLayout>
</template>
