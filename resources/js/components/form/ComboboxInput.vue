<script lang="ts" setup>
import { Button } from '@/components/ui/button';
import {
    Command,
    CommandEmpty,
    CommandGroup,
    CommandInput,
    CommandItem,
    CommandList,
} from '@/components/ui/command';
import {
    Field,
    FieldDescription,
    FieldError,
    FieldLabel,
} from '@/components/ui/field';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import { cn } from '@/lib/utils';
import { CheckIcon, ChevronsUpDownIcon } from 'lucide-vue-next';
import { computed, ref } from 'vue';

interface Props<T = any> {
    label?: string;
    description?: string;
    placeholder?: string;
    options: T[];
    optionLabel?: keyof T | ((option: T) => string);
    optionValue?: keyof T | ((option: T) => string | number);
    error?: string;
}

type ID = number | string;

const props = defineProps<Props>();

const model = defineModel<ID | null>({
    default: null,
});

const emit = defineEmits<{
    (e: 'validate'): void;
}>();

const open = ref(false);

function getOptionLabel(option: any): string {
    if (typeof props.optionLabel === 'function') {
        return props.optionLabel(option);
    }

    if (typeof props.optionLabel === 'string') {
        return option[props.optionLabel];
    }

    return option.name ?? option.label ?? String(option);
}

function getOptionValue(option: any): string | number {
    if (typeof props.optionValue === 'function') {
        return props.optionValue(option);
    }

    if (typeof props.optionValue === 'string') {
        return option[props.optionValue];
    }

    return option.id ?? option.value ?? option;
}

const optionMap = computed(() => {
    return new Map(
        props.options.map((option) => [
            getOptionValue(option),
            getOptionLabel(option),
        ]),
    );
});

const selectedLabel = computed(() => {
    if (model.value == null) return null;
    return optionMap.value.get(model.value) ?? null;
});

function selectOption(id: ID) {
    model.value = id === model.value ? null : id;
    open.value = false;
    emit('validate');
}
</script>

<template>
    <Field :data-invalid="!!props.error">
        <FieldLabel v-if="props.label">
            {{ props.label }}
        </FieldLabel>

        <Popover v-model:open="open">
            <PopoverTrigger as-child>
                <Button
                    :aria-expanded="open"
                    class="w-full justify-between"
                    role="combobox"
                    variant="outline"
                >
                    <span class="truncate">
                        {{
                            selectedLabel || props.placeholder || 'Seleccionar'
                        }}
                    </span>
                    <ChevronsUpDownIcon class="opacity-50" />
                </Button>
            </PopoverTrigger>

            <PopoverContent class="w-(--reka-popper-anchor-width) p-0">
                <Command>
                    <CommandInput
                        :placeholder="props.placeholder || 'Buscar...'"
                        class="h-9"
                    />

                    <CommandList>
                        <CommandEmpty> Sin resultados. </CommandEmpty>

                        <CommandGroup>
                            <CommandItem
                                v-for="option in props.options"
                                :key="getOptionValue(option)"
                                :value="String(getOptionValue(option))"
                                @select="
                                    () => selectOption(getOptionValue(option))
                                "
                            >
                                {{ getOptionLabel(option) }}
                                <CheckIcon
                                    :class="
                                        cn(
                                            'ml-auto',
                                            model === option.id
                                                ? 'opacity-100'
                                                : 'opacity-0',
                                        )
                                    "
                                />
                            </CommandItem>
                        </CommandGroup>
                    </CommandList>
                </Command>
            </PopoverContent>
        </Popover>

        <FieldDescription v-if="props.description">
            {{ props.description }}
        </FieldDescription>

        <FieldError v-if="props.error" :errors="[props.error]" />
    </Field>
</template>
