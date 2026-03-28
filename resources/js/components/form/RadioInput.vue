<script lang="ts" setup>
import {
    Field,
    FieldDescription,
    FieldError,
    FieldLabel,
    FieldSet,
} from '@/components/ui/field';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';

interface Props<T = any> {
    label?: string;
    description?: string;
    options: T[];
    optionLabel?: keyof T | ((option: T) => string);
    optionValue?: keyof T | ((option: T) => string | number);
    error?: string | undefined;
}

const props = defineProps<Props>();

defineOptions({
    inheritAttrs: false,
});

const model = defineModel<string | number | undefined | null>();

const emit = defineEmits<{
    (e: 'validate'): void;
}>();

function getOptionLabel(option: any): string {
    if (typeof props.optionLabel === 'function') {
        return props.optionLabel(option);
    }

    if (typeof props.optionLabel === 'string') {
        return option[props.optionLabel];
    }

    return option.name ?? option.label ?? String(option);
}

function getOptionValue(option: any): string | number {
    if (typeof props.optionValue === 'function') {
        return props.optionValue(option);
    }

    if (typeof props.optionValue === 'string') {
        return option[props.optionValue];
    }

    return option.id ?? option.value ?? option;
}
</script>

<template>
    <Field :data-invalid="!!props.error">
        <FieldSet>
            <FieldLabel v-if="props.label">
                {{ props.label }}
            </FieldLabel>
            <FieldDescription v-if="props.description">
                {{ props.description }}
            </FieldDescription>

            <RadioGroup v-model="model" @update:modelValue="emit('validate')">
                <Field
                    v-for="option in props.options"
                    :key="getOptionValue(option)"
                    orientation="horizontal"
                >
                    <RadioGroupItem
                        :id="getOptionValue(option) + getOptionLabel(option)"
                        :value="getOptionValue(option)"
                    />
                    <FieldLabel
                        :for="getOptionValue(option) + getOptionLabel(option)"
                        class="font-normal"
                    >
                        {{ getOptionLabel(option) }}
                    </FieldLabel>
                </Field>
            </RadioGroup>
        </FieldSet>

        <FieldError v-if="props.error" :errors="[props.error]" />
    </Field>
</template>
