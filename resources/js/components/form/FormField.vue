<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import {
    Field,
    FieldDescription,
    FieldError,
    FieldLabel,
} from '@/components/ui/field';

/**
 * Label, description and error wrapper shared by every form input.
 */
const {
    label,
    for: htmlFor,
    description,
    error,
    class: className,
} = defineProps<{
    label?: string;
    for?: string;
    description?: string;
    error?: string;
    class?: HTMLAttributes['class'];
}>();
</script>

<template>
    <Field :data-invalid="!!error || undefined" :class="className">
        <slot name="label">
            <FieldLabel v-if="label" :for="htmlFor">{{ label }}</FieldLabel>
        </slot>

        <slot />

        <FieldDescription v-if="description">{{
            description
        }}</FieldDescription>

        <FieldError v-if="error" :errors="[error]" />
    </Field>
</template>
