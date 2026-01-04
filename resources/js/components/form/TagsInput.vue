<script lang="ts" setup>
import {
    Field,
    FieldDescription,
    FieldError,
    FieldLabel,
} from '@/components/ui/field';
import {
    TagsInput,
    TagsInputInput,
    TagsInputItem,
    TagsInputItemDelete,
    TagsInputItemText,
} from '@/components/ui/tags-input';

interface Props {
    label?: string;
    description?: string;
    max: number;
    error?: string | undefined;
}

const props = defineProps<Props>();

const emit = defineEmits<{
    (e: 'validate'): void;
}>();

defineOptions({
    inheritAttrs: false,
});

const model = defineModel<string[]>({
    default: [],
});
</script>

<template>
    <Field :data-invalid="!!props.error">
        <FieldLabel v-if="props.label">
            {{ props.label }}
        </FieldLabel>

        <TagsInput
            v-model="model"
            :max="props.max"
            @update:modelValue="emit('validate')"
        >
            <TagsInputItem v-for="item in model" :key="item" :value="item">
                <TagsInputItemText />
                <TagsInputItemDelete />
            </TagsInputItem>
            <TagsInputInput v-bind="$attrs" />
        </TagsInput>

        <FieldDescription v-if="props.description">
            {{ props.description }}
        </FieldDescription>

        <FieldError v-if="props.error" :errors="[props.error]" />
    </Field>
</template>
