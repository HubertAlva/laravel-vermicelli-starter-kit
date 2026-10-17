<script setup lang="ts">
import { IMaskComponent } from 'vue-imask';
import { cn } from '@/lib/utils';
import FormField from './FormField.vue';
import type { BaseInputProps } from './types';

defineOptions({ inheritAttrs: false });

/**
 * Masked phone input. The model holds the unmasked digits only.
 */
const {
    id,
    label,
    description,
    error,
    mask = '000 000 000',
} = defineProps<BaseInputProps & { mask?: string }>();

const model = defineModel<string | null>();

const emit = defineEmits<{ validate: [] }>();
</script>

<template>
    <FormField :label :for="id" :description :error>
        <IMaskComponent
            :id
            v-model:unmasked="model"
            :mask
            type="tel"
            inputmode="tel"
            :aria-invalid="!!error || undefined"
            :class="
                cn(
                    'h-9 w-full min-w-0 rounded-md border border-input bg-transparent px-3 py-1 text-base shadow-xs transition-[color,box-shadow] outline-none placeholder:text-muted-foreground disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm dark:bg-input/30',
                    'focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50',
                    'aria-invalid:border-destructive aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40',
                )
            "
            v-bind="$attrs"
            @accept="emit('validate')"
        />
    </FormField>
</template>
