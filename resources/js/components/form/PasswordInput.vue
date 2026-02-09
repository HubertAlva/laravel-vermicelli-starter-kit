<script lang="ts" setup>
import {
    Field,
    FieldDescription,
    FieldError,
    FieldLabel,
} from '@/components/ui/field';
import {
    InputGroup,
    InputGroupAddon,
    InputGroupButton,
    InputGroupInput,
} from '@/components/ui/input-group';
import { Eye, EyeOff } from 'lucide-vue-next';
import { ref } from 'vue';

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

const model = defineModel<string | number | undefined>();

const showPassword = ref(false);

const togglePassword = () => {
    showPassword.value = !showPassword.value;
};
</script>

<template>
    <Field :data-invalid="!!props.error">
        <FieldLabel v-if="props.label" :for="props.id">
            {{ props.label }}
        </FieldLabel>

        <InputGroup>
            <InputGroupInput
                :id="props.id"
                v-model="model"
                :type="showPassword ? 'text' : 'password'"
                v-bind="$attrs"
            />
            <InputGroupAddon align="inline-end">
                <InputGroupButton
                    aria-label="Show"
                    size="icon-xs"
                    title="Show"
                    type="button"
                    @click="togglePassword"
                >
                    <Eye v-if="showPassword" />
                    <EyeOff v-else />
                </InputGroupButton>
            </InputGroupAddon>
        </InputGroup>

        <FieldDescription v-if="props.description">
            {{ props.description }}
        </FieldDescription>

        <FieldError v-if="props.error" :errors="[props.error]" />
    </Field>
</template>
