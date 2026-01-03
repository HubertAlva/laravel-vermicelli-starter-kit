<script lang="ts" setup>
import {
    Field,
    FieldDescription,
    FieldError,
    FieldLabel,
    FieldSet,
} from '@/components/ui/field';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';

interface Props {
    label?: string;
    description?: string;
    options: { label: string; value: string }[];
    error?: string | undefined;
}

const props = defineProps<Props>();

defineOptions({
    inheritAttrs: false,
});

const model = defineModel<string | number | undefined>();
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

            <RadioGroup v-model="model">
                <Field
                    v-for="option in props.options"
                    :key="option.value"
                    orientation="horizontal"
                >
                    <RadioGroupItem :id="option.value" :value="option.value" />
                    <FieldLabel :for="option.value" class="font-normal">
                        {{ option.label }}
                    </FieldLabel>
                </Field>
            </RadioGroup>
        </FieldSet>

        <FieldError v-if="props.error" :errors="[props.error]" />
    </Field>
</template>
