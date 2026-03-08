<script setup lang="ts">
import { cn } from '@/lib/utils';
import type { HTMLAttributes } from 'vue';

const props = defineProps<{
    class?: HTMLAttributes['class'];
}>();
</script>

<template>
    <h1 :class="cn('text-2xl font-semibold', props.class)">
        <slot />
    </h1>
</template>
