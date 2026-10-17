<script setup lang="ts">
import { Eye, EyeOff } from '@lucide/vue';
import { ref } from 'vue';
import {
    InputGroup,
    InputGroupAddon,
    InputGroupButton,
    InputGroupInput,
} from '@/components/ui/input-group';
import FormField from './FormField.vue';
import type { BaseInputProps } from './types';

defineOptions({ inheritAttrs: false });

const { id, label, description, error } = defineProps<BaseInputProps>();

const model = defineModel<string | null>();

const emit = defineEmits<{ validate: [] }>();

const isVisible = ref(false);
</script>

<template>
    <FormField :label :for="id" :description :error>
        <InputGroup>
            <InputGroupInput
                :id
                v-model="model"
                :type="isVisible ? 'text' : 'password'"
                :aria-invalid="!!error || undefined"
                v-bind="$attrs"
                @update:model-value="emit('validate')"
            />
            <InputGroupAddon align="inline-end">
                <InputGroupButton
                    size="icon-xs"
                    type="button"
                    :aria-label="
                        isVisible ? 'Ocultar contraseña' : 'Mostrar contraseña'
                    "
                    @click="isVisible = !isVisible"
                >
                    <EyeOff v-if="isVisible" />
                    <Eye v-else />
                </InputGroupButton>
            </InputGroupAddon>
        </InputGroup>
    </FormField>
</template>
