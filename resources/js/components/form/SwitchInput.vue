<script setup lang="ts">
import {
    Field,
    FieldContent,
    FieldDescription,
    FieldError,
    FieldLabel,
} from '@/components/ui/field';
import { Switch } from '@/components/ui/switch';
import type { BaseInputProps } from './types';

defineOptions({ inheritAttrs: false });

const { id, label, description, error } = defineProps<
    BaseInputProps & { id: string }
>();

const model = defineModel<boolean>({ default: false });

const emit = defineEmits<{ validate: [] }>();
</script>

<template>
    <Field :data-invalid="!!error || undefined">
        <Field orientation="horizontal">
            <FieldContent>
                <FieldLabel v-if="label" :for="id">{{ label }}</FieldLabel>
                <FieldDescription v-if="description">{{
                    description
                }}</FieldDescription>
            </FieldContent>

            <Switch
                :id
                v-model="model"
                v-bind="$attrs"
                @update:model-value="emit('validate')"
            />
        </Field>

        <FieldError v-if="error" :errors="[error]" />
    </Field>
</template>
