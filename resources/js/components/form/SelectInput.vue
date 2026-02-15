<script lang="ts" setup>
import {
    Field,
    FieldDescription,
    FieldError,
    FieldLabel,
} from '@/components/ui/field';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { AcceptableValue } from 'reka-ui';
import { computed } from 'vue';

interface Props<T = any> {
    label?: string;
    description?: string;
    error?: string | undefined;
    multiple?: boolean;
    options: T[];
    optionLabel?: keyof T | ((option: T) => string);
    optionValue?: keyof T | ((option: T) => string | number);
    hasEmptyOption?: boolean;
}

type SelectValue = string | number | null | string[] | number[];

type OptionItem = {
    value: AcceptableValue;
    label: string;
};

const {
    label,
    description,
    error,
    multiple = false,
    options,
    optionLabel,
    optionValue,
    hasEmptyOption = true,
} = defineProps<Props>();

defineOptions({
    inheritAttrs: false,
});

const model = defineModel<SelectValue>();

const emit = defineEmits<{
    (e: 'validate'): void;
}>();

function getOptionLabel(option: any): string {
    if (typeof optionLabel === 'function') {
        return optionLabel(option);
    }

    if (typeof optionLabel === 'string') {
        return option[optionLabel];
    }

    return option.name ?? option.label ?? String(option);
}

function getOptionValue(option: any): string | number {
    if (typeof optionValue === 'function') {
        return optionValue(option);
    }

    if (typeof optionValue === 'string') {
        return option[optionValue];
    }

    return option.id ?? option.value ?? option;
}

const normalizedOptions = computed<OptionItem[]>(() => {
    const mapped: OptionItem[] = options.map((option) => ({
        value: getOptionValue(option),
        label: getOptionLabel(option),
    }));

    if (!multiple && hasEmptyOption) {
        mapped.unshift({
            value: null,
            label: 'Sin selección',
        });
    }

    return mapped;
});
</script>

<template>
    <Field :data-invalid="!!error">
        <FieldLabel v-if="label">
            {{ label }}
        </FieldLabel>

        <Select
            v-model="model"
            :multiple="multiple"
            @update:modelValue="emit('validate')"
        >
            <SelectTrigger class="w-full">
                <SelectValue v-bind="$attrs" />
            </SelectTrigger>
            <SelectContent>
                <SelectGroup>
                    <SelectItem
                        v-for="option in normalizedOptions"
                        :key="String(option.value)"
                        :value="option.value"
                    >
                        {{ option.label }}
                    </SelectItem>
                </SelectGroup>
            </SelectContent>
        </Select>

        <FieldDescription v-if="description">
            {{ description }}
        </FieldDescription>

        <FieldError v-if="error" :errors="[error]" />
    </Field>
</template>
