<script lang="ts" setup>
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from '@/components/ui/tooltip';

const props = defineProps<{
    text: string;
    side?: 'top' | 'bottom' | 'left' | 'right' | undefined;
}>();
</script>

<template>
    <TooltipProvider :delayDuration="300">
        <Tooltip>
            <TooltipTrigger as-child>
                <slot />
            </TooltipTrigger>
            <TooltipContent :side="props.side" class="max-w-[200px]">
                <p class="text-pretty">
                    {{ props.text }}
                </p>
            </TooltipContent>
        </Tooltip>
    </TooltipProvider>
</template>
