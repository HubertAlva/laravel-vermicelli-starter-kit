<script setup lang="ts">
import {
    TagsInput,
    TagsInputInput,
    TagsInputItem,
    TagsInputItemDelete,
    TagsInputItemText,
} from '@/components/ui/tags-input';
import FormField from './FormField.vue';
import type { BaseInputProps } from './types';

defineOptions({ inheritAttrs: false });

/**
 * Free-form tags: type a value and press Enter to add it.
 */
const { id, label, description, error, max } = defineProps<
    BaseInputProps & { max?: number }
>();

const model = defineModel<string[] | null>({ default: null });

const emit = defineEmits<{ validate: [] }>();

function update(value: string[]): void {
    model.value = value;
    emit('validate');
}
</script>

<template>
    <FormField :label :for="id" :description :error>
        <TagsInput
            :model-value="model ?? []"
            :max
            :aria-invalid="!!error || undefined"
            @update:model-value="(value) => update(value as string[])"
        >
            <TagsInputItem
                v-for="item in model ?? []"
                :key="item"
                :value="item"
            >
                <TagsInputItemText />
                <TagsInputItemDelete />
            </TagsInputItem>
            <TagsInputInput :id v-bind="$attrs" />
        </TagsInput>
    </FormField>
</template>
