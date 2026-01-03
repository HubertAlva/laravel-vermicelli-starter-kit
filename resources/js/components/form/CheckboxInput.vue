<script lang="ts" setup>
import { Checkbox } from '@/components/ui/checkbox';
import {
    Field,
    FieldContent,
    FieldDescription,
    FieldError,
    FieldLabel,
} from '@/components/ui/field';

interface Props {
    label: string;
    id: string;
    description?: string;
    error?: string | undefined;
}

const props = defineProps<Props>();

const model = defineModel<boolean | undefined>();

const handleChange = (value: boolean | 'indeterminate') => {
    model.value = value === 'indeterminate' ? undefined : value;
};
</script>

<template>
    <Field :data-invalid="!!props.error" orientation="horizontal">
        <Checkbox
            :id="props.id"
            v-model="model"
            @update:model-value="handleChange"
        />

        <FieldContent>
            <FieldLabel :for="props.id">
                {{ props.label }}
            </FieldLabel>

            <FieldDescription v-if="props.description">
                {{ props.description }}
            </FieldDescription>

            <FieldError v-if="props.error" :errors="[props.error]" />
        </FieldContent>
    </Field>
</template>
