<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';

const { as = 'section', class: className } = defineProps<{
    as?: string;
    class?: HTMLAttributes['class'];
}>();
</script>

<template>
    <component
        :is="as"
        :class="
            cn(
                'mx-auto max-w-6xl space-y-6 px-5 py-12 lg:px-4 lg:py-16',
                className,
            )
        "
    >
        <slot />
    </component>
</template>
