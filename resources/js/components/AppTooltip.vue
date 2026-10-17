<script setup lang="ts">
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from '@/components/ui/tooltip';

const { text, side = 'top' } = defineProps<{
    text: string;
    side?: 'top' | 'right' | 'bottom' | 'left';
}>();
</script>

<template>
    <TooltipProvider :delay-duration="300">
        <Tooltip>
            <TooltipTrigger as-child>
                <slot />
            </TooltipTrigger>
            <TooltipContent :side class="max-w-52 text-pretty">
                {{ text }}
            </TooltipContent>
        </Tooltip>
    </TooltipProvider>
</template>
