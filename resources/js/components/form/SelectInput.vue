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
import { computed } from 'vue';

interface Props {
    label?: string;
    description?: string;
    error?: string | undefined;
    multiple?: boolean;
    options: Array<any>;
}

type SelectValue = string | number | null | string[] | number[];

const props = defineProps<Props>();

defineOptions({
    inheritAttrs: false,
});

const model = defineModel<SelectValue>();

const emit = defineEmits<{
    (e: 'validate'): void;
}>();

const options = computed(() => {
    return props.options.map((option) => {
        return {
            value: option.id,
            label: option.name,
        };
    });
});

if (!props.multiple) {
    options.value.unshift({
        value: null,
        label: 'Sin selección',
    });
}
</script>

<template>
    <Field :data-invalid="!!props.error">
        <FieldLabel v-if="props.label">
            {{ props.label }}
        </FieldLabel>

        <Select
            v-model="model"
            :multiple="props.multiple"
            @update:modelValue="emit('validate')"
        >
            <SelectTrigger class="w-full">
                <SelectValue v-bind="$attrs" />
            </SelectTrigger>
            <SelectContent>
                <SelectGroup>
                    <SelectItem
                        v-for="option in options"
                        :key="option.value"
                        :value="option.value"
                    >
                        {{ option.label }}
                    </SelectItem>
                </SelectGroup>
            </SelectContent>
        </Select>

        <FieldDescription v-if="props.description">
            {{ props.description }}
        </FieldDescription>

        <FieldError v-if="props.error" :errors="[props.error]" />
    </Field>
</template>
