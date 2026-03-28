<script lang="ts" setup>
import { cn } from '@/lib/utils';
import type { HTMLAttributes } from 'vue';

const props = defineProps<{
    as?: string;
    class?: HTMLAttributes['class'];
}>();
</script>

<template>
    <component
        :is="props.as ?? 'section'"
        :class="
            cn(
                'mx-auto max-w-6xl space-y-6 px-5 py-12 lg:px-4 lg:py-16',
                props.class,
            )
        "
    >
        <slot />
    </component>
</template>
