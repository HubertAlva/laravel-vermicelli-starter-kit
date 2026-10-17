<script setup lang="ts">
import { Input } from '@/components/ui/input';
import FormField from './FormField.vue';
import type { BaseInputProps } from './types';

defineOptions({ inheritAttrs: false });

const { id, label, description, error } = defineProps<BaseInputProps>();

const model = defineModel<string | number | null>();

const emit = defineEmits<{ validate: [] }>();
</script>

<template>
    <FormField :label :for="id" :description :error>
        <Input
            :id
            :model-value="model ?? undefined"
            :aria-invalid="!!error || undefined"
            v-bind="$attrs"
            @update:model-value="
                (value: string | number) => {
                    model = value;
                    emit('validate');
                }
            "
        />
    </FormField>
</template>
