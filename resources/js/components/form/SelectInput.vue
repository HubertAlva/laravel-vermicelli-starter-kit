<script setup lang="ts" generic="T">
import { computed } from 'vue';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { normalizeOptions } from '@/lib/options';
import type { OptionAccessor, OptionValue } from '@/lib/options';
import FormField from './FormField.vue';
import type { BaseInputProps } from './types';

defineOptions({ inheritAttrs: false });

const props = withDefaults(
    defineProps<
        BaseInputProps & {
            options: T[];
            optionLabel?: OptionAccessor<T, string>;
            optionValue?: OptionAccessor<T, OptionValue>;
            placeholder?: string;
            multiple?: boolean;
            /** Adds a "no selection" item to single selects. */
            clearable?: boolean;
        }
    >(),
    {
        placeholder: 'Selecciona una opción',
        multiple: false,
        clearable: true,
    },
);

const model = defineModel<OptionValue | OptionValue[] | null>();

const emit = defineEmits<{ validate: [] }>();

const items = computed(() =>
    normalizeOptions(props.options, props.optionLabel, props.optionValue),
);
</script>

<template>
    <FormField :label :for="id" :description :error>
        <Select
            v-model="model"
            :multiple
            @update:model-value="emit('validate')"
        >
            <SelectTrigger
                :id
                class="w-full"
                :aria-invalid="!!error || undefined"
                v-bind="$attrs"
            >
                <SelectValue :placeholder />
            </SelectTrigger>
            <SelectContent>
                <SelectGroup>
                    <SelectItem v-if="clearable && !multiple" :value="null">
                        Sin selección
                    </SelectItem>
                    <SelectItem
                        v-for="item in items"
                        :key="item.value"
                        :value="item.value"
                    >
                        {{ item.label }}
                    </SelectItem>
                </SelectGroup>
            </SelectContent>
        </Select>
    </FormField>
</template>
