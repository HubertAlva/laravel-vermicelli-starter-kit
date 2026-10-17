<script setup lang="ts">
import { Upload } from '@lucide/vue';
import type { HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';

/**
 * Clickable drag-and-drop area used by the file and image inputs.
 */
const {
    isDragging = false,
    invalid = false,
    text = 'Arrastra y suelta o selecciona un archivo',
    class: className,
} = defineProps<{
    isDragging?: boolean;
    invalid?: boolean;
    text?: string;
    class?: HTMLAttributes['class'];
}>();
</script>

<template>
    <button
        type="button"
        :class="
            cn(
                'flex min-h-36 w-full cursor-pointer flex-col items-center justify-center gap-3 rounded-lg border-2 border-dashed border-input bg-muted/40 p-4 text-center transition hover:border-primary/50 hover:bg-primary/5',
                isDragging && 'border-primary/50 bg-primary/5',
                invalid && 'border-destructive bg-destructive/5',
                className,
            )
        "
    >
        <slot>
            <Upload class="size-6 text-muted-foreground" />
            <span class="text-sm text-muted-foreground">{{ text }}</span>
        </slot>
    </button>
</template>
