<script setup lang="ts">
/**
 * Sticky page header for admin forms: title and badges on the left, actions
 * (save, delete...) on the right.
 */
const { title } = defineProps<{ title: string }>();
</script>

<template>
    <div
        class="sticky top-0 z-10 -mx-4 -mt-4 mb-4 flex flex-wrap items-center justify-between gap-4 bg-muted px-4 py-4 dark:bg-neutral-900"
    >
        <div class="flex min-w-0 flex-wrap items-center gap-2">
            <slot name="title">
                <h1 class="truncate text-2xl font-semibold">{{ title }}</h1>
            </slot>
            <slot name="badges" />
        </div>

        <div class="flex flex-wrap items-center justify-end gap-2">
            <slot name="actions" />
        </div>
    </div>
</template>
