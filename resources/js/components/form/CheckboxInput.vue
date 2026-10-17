<script setup lang="ts">
import { Checkbox } from '@/components/ui/checkbox';
import {
    Field,
    FieldContent,
    FieldDescription,
    FieldError,
    FieldLabel,
} from '@/components/ui/field';
import type { BaseInputProps } from './types';

const { id, label, description, error } = defineProps<
    BaseInputProps & { id: string; label: string }
>();

const model = defineModel<boolean>({ default: false });

const emit = defineEmits<{ validate: [] }>();

function update(value: boolean | 'indeterminate'): void {
    model.value = value === true;
    emit('validate');
}
</script>

<template>
    <Field orientation="horizontal" :data-invalid="!!error || undefined">
        <Checkbox
            :id
            :model-value="model"
            :aria-invalid="!!error || undefined"
            @update:model-value="update"
        />

        <FieldContent>
            <FieldLabel :for="id">{{ label }}</FieldLabel>
            <FieldDescription v-if="description">{{
                description
            }}</FieldDescription>
            <FieldError v-if="error" :errors="[error]" />
        </FieldContent>
    </Field>
</template>
