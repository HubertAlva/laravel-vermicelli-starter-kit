<script lang="ts" setup>
import {
    Field,
    FieldDescription,
    FieldError,
    FieldLabel,
} from '@/components/ui/field';
import { Textarea } from '@/components/ui/textarea';

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

const model = defineModel<string | undefined>();
</script>

<template>
    <Field :data-invalid="!!props.error">
        <FieldLabel v-if="props.label" :for="props.id">
            {{ props.label }}
        </FieldLabel>

        <Textarea
            :id="props.id"
            v-model="model"
            class="min-h-28"
            v-bind="$attrs"
        />

        <FieldDescription v-if="props.description">
            {{ props.description }}
        </FieldDescription>

        <FieldError v-if="props.error" :errors="[props.error]" />
    </Field>
</template>
