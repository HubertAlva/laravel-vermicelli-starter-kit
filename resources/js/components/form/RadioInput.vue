<script setup lang="ts" generic="T">
import { computed, useId } from 'vue';
import { Field, FieldLabel } from '@/components/ui/field';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import { normalizeOptions } from '@/lib/options';
import type { OptionAccessor, OptionValue } from '@/lib/options';
import FormField from './FormField.vue';
import type { BaseInputProps } from './types';

defineOptions({ inheritAttrs: false });

const props = defineProps<
    BaseInputProps & {
        options: T[];
        optionLabel?: OptionAccessor<T, string>;
        optionValue?: OptionAccessor<T, OptionValue>;
    }
>();

const model = defineModel<OptionValue | null>();

const emit = defineEmits<{ validate: [] }>();

const baseId = props.id ?? useId();

const items = computed(() =>
    normalizeOptions(props.options, props.optionLabel, props.optionValue),
);
</script>

<template>
    <FormField :label="label" :description="description" :error="error">
        <RadioGroup
            v-model="model"
            :aria-invalid="!!error || undefined"
            v-bind="$attrs"
            @update:model-value="emit('validate')"
        >
            <Field
                v-for="item in items"
                :key="item.value"
                orientation="horizontal"
            >
                <RadioGroupItem
                    :id="`${baseId}-${item.value}`"
                    :value="item.value"
                />
                <FieldLabel
                    :for="`${baseId}-${item.value}`"
                    class="font-normal"
                >
                    {{ item.label }}
                </FieldLabel>
            </Field>
        </RadioGroup>
    </FormField>
</template>
