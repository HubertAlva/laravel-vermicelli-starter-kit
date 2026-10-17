<script setup lang="ts">
import { Textarea } from '@/components/ui/textarea';
import FormField from './FormField.vue';
import type { BaseInputProps } from './types';

defineOptions({ inheritAttrs: false });

const { id, label, description, error } = defineProps<BaseInputProps>();

const model = defineModel<string | null>();

const emit = defineEmits<{ validate: [] }>();
</script>

<template>
    <FormField :label :for="id" :description :error>
        <Textarea
            :id
            :model-value="model ?? undefined"
            class="min-h-28"
            :aria-invalid="!!error || undefined"
            v-bind="$attrs"
            @update:model-value="
                (value: string | number) => {
                    model = String(value);
                    emit('validate');
                }
            "
        />
    </FormField>
</template>
