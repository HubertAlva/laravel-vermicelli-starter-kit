<script lang="ts" setup>
import {
    Field,
    FieldContent,
    FieldDescription,
    FieldError,
    FieldLabel,
} from '@/components/ui/field';
import { Switch } from '@/components/ui/switch';

interface Props {
    label?: string;
    id: string;
    description?: string;
    error?: string | undefined;
}

const props = defineProps<Props>();

defineOptions({
    inheritAttrs: false,
});

const model = defineModel<boolean | undefined>();

const handleChange = (value: boolean) => {
    model.value = value;
};
</script>

<template>
    <Field :data-invalid="!!props.error">
        <Field orientation="horizontal">
            <FieldContent>
                <FieldLabel v-if="props.label" :for="props.id">
                    {{ props.label }}
                </FieldLabel>

                <FieldDescription v-if="props.description">
                    {{ props.description }}
                </FieldDescription>
            </FieldContent>

            <Switch
                :id="props.id"
                v-model="model"
                v-bind="$attrs"
                @update:model-value="handleChange"
            />
        </Field>

        <FieldError v-if="props.error" :errors="[props.error]" />
    </Field>
</template>
