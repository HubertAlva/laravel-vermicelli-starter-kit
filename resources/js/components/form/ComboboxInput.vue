<script setup lang="ts" generic="T">
import { CheckIcon, ChevronsUpDownIcon } from '@lucide/vue';
import { computed, ref } from 'vue';
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
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import { normalizeOptions } from '@/lib/options';
import type { OptionAccessor, OptionValue } from '@/lib/options';
import { cn } from '@/lib/utils';
import FormField from './FormField.vue';
import type { BaseInputProps } from './types';

const props = withDefaults(
    defineProps<
        BaseInputProps & {
            options: T[];
            optionLabel?: OptionAccessor<T, string>;
            optionValue?: OptionAccessor<T, OptionValue>;
            placeholder?: string;
            searchPlaceholder?: string;
        }
    >(),
    {
        placeholder: 'Seleccionar',
        searchPlaceholder: 'Buscar...',
    },
);

const model = defineModel<OptionValue | null>({ default: null });

const emit = defineEmits<{ validate: [] }>();

const open = ref(false);

const items = computed(() =>
    normalizeOptions(props.options, props.optionLabel, props.optionValue),
);

const selectedLabel = computed(
    () => items.value.find((item) => item.value === model.value)?.label,
);

function select(value: OptionValue): void {
    model.value = value === model.value ? null : value;
    open.value = false;
    emit('validate');
}
</script>

<template>
    <FormField :label :for="id" :description :error>
        <Popover v-model:open="open">
            <PopoverTrigger as-child>
                <Button
                    :id
                    variant="outline"
                    role="combobox"
                    :aria-expanded="open"
                    :aria-invalid="!!error || undefined"
                    :class="
                        cn(
                            'w-full justify-between font-normal',
                            !selectedLabel && 'text-muted-foreground',
                        )
                    "
                >
                    <span class="truncate">{{
                        selectedLabel ?? placeholder
                    }}</span>
                    <ChevronsUpDownIcon class="opacity-50" />
                </Button>
            </PopoverTrigger>

            <PopoverContent class="w-(--reka-popper-anchor-width) p-0">
                <Command>
                    <CommandInput
                        :placeholder="searchPlaceholder"
                        class="h-9"
                    />
                    <CommandList>
                        <CommandEmpty>Sin resultados.</CommandEmpty>
                        <CommandGroup>
                            <!-- Value is the label so the search matches what users see -->
                            <CommandItem
                                v-for="item in items"
                                :key="item.value"
                                :value="item.label"
                                @select="select(item.value)"
                            >
                                {{ item.label }}
                                <CheckIcon
                                    :class="
                                        cn(
                                            'ml-auto',
                                            item.value === model
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
    </FormField>
</template>
