<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
import admin from '@/routes/admin';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: admin.dashboard(),
            },
        ],
    },
});
</script>

<template>
    <Head title="Dashboard" />

    <div class="flex flex-col gap-4">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div
                v-for="n in 3"
                :key="n"
                class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 bg-background dark:border-sidebar-border"
            >
                <PlaceholderPattern />
            </div>
        </div>
        <div
            class="relative min-h-[50vh] overflow-hidden rounded-xl border border-sidebar-border/70 bg-background dark:border-sidebar-border"
        >
            <PlaceholderPattern />
        </div>
    </div>
</template>
