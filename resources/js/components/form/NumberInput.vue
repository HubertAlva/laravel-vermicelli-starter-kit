<script setup lang="ts">
import {
    NumberField,
    NumberFieldContent,
    NumberFieldDecrement,
    NumberFieldIncrement,
    NumberFieldInput,
} from '@/components/ui/number-field';
import FormField from './FormField.vue';
import type { BaseInputProps } from './types';

defineOptions({ inheritAttrs: false });

const { id, label, description, error } = defineProps<BaseInputProps>();

const model = defineModel<number | null>();

const emit = defineEmits<{ validate: [] }>();
</script>

<template>
    <FormField :label :for="id" :description :error>
        <NumberField
            :id
            :model-value="model ?? undefined"
            v-bind="$attrs"
            @update:model-value="
                (value: number | undefined) => {
                    model =
                        value === undefined || Number.isNaN(value)
                            ? null
                            : value;
                    emit('validate');
                }
            "
        >
            <NumberFieldContent>
                <NumberFieldDecrement />
                <NumberFieldInput :aria-invalid="!!error || undefined" />
                <NumberFieldIncrement />
            </NumberFieldContent>
        </NumberField>
    </FormField>
</template>
