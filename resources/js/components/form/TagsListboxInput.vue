<script setup lang="ts" generic="T">
import { CheckIcon, ChevronDown } from '@lucide/vue';
import {
    ListboxContent,
    ListboxFilter,
    ListboxItem,
    ListboxItemIndicator,
    ListboxRoot,
    useFilter,
} from 'reka-ui';
import { computed, ref, watch } from 'vue';
import { Button } from '@/components/ui/button';
import {
    Popover,
    PopoverAnchor,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import {
    TagsInput,
    TagsInputInput,
    TagsInputItem,
    TagsInputItemDelete,
} from '@/components/ui/tags-input';
import { normalizeOptions } from '@/lib/options';
import type { OptionAccessor, OptionValue } from '@/lib/options';
import FormField from './FormField.vue';
import type { BaseInputProps } from './types';

/**
 * Multiple selection from a predefined, searchable list of options.
 */
const props = withDefaults(
    defineProps<
        BaseInputProps & {
            options: T[];
            optionLabel?: OptionAccessor<T, string>;
            optionValue?: OptionAccessor<T, OptionValue>;
            placeholder?: string;
        }
    >(),
    {
        placeholder: 'Opciones...',
    },
);

const model = defineModel<OptionValue[] | null>({ default: null });

const emit = defineEmits<{ validate: [] }>();

const searchTerm = ref('');
const open = ref(false);

const { contains } = useFilter({ sensitivity: 'base' });

const items = computed(() =>
    normalizeOptions(props.options, props.optionLabel, props.optionValue),
);

const labels = computed(
    () => new Map(items.value.map((item) => [item.value, item.label])),
);

const filteredItems = computed(() =>
    searchTerm.value === ''
        ? items.value
        : items.value.filter((item) => contains(item.label, searchTerm.value)),
);

const selected = computed({
    get: () => model.value ?? [],
    set: (value: OptionValue[]) => {
        model.value = value;
        emit('validate');
    },
});

watch(searchTerm, (term) => {
    if (term) {
        open.value = true;
    }
});
</script>

<template>
    <FormField :label :for="id" :description :error>
        <Popover v-model:open="open">
            <ListboxRoot v-model="selected" highlight-on-hover multiple>
                <PopoverAnchor>
                    <TagsInput
                        v-model="selected"
                        class="w-full"
                        :aria-invalid="!!error || undefined"
                    >
                        <TagsInputItem
                            v-for="value in selected"
                            :key="value"
                            :value="value"
                        >
                            <span class="rounded px-2 py-0.5 text-sm">
                                {{ labels.get(value) ?? value }}
                            </span>
                            <TagsInputItemDelete />
                        </TagsInputItem>

                        <ListboxFilter v-model="searchTerm" as-child>
                            <TagsInputInput
                                :id
                                :placeholder
                                @keydown.enter.prevent
                                @keydown.down="open = true"
                            />
                        </ListboxFilter>

                        <PopoverTrigger as-child>
                            <Button
                                class="order-last ml-auto self-start"
                                size="icon-sm"
                                variant="ghost"
                                aria-label="Mostrar opciones"
                            >
                                <ChevronDown class="size-3.5" />
                            </Button>
                        </PopoverTrigger>
                    </TagsInput>
                </PopoverAnchor>

                <PopoverContent
                    class="w-(--reka-popper-anchor-width) p-1"
                    @open-auto-focus.prevent
                >
                    <ListboxContent
                        class="max-h-75 scroll-py-1 overflow-x-hidden overflow-y-auto"
                        tabindex="0"
                    >
                        <ListboxItem
                            v-for="item in filteredItems"
                            :key="item.value"
                            :value="item.value"
                            class="relative flex cursor-default items-center gap-2 rounded-sm px-2 py-1.5 text-sm outline-hidden select-none data-disabled:pointer-events-none data-disabled:opacity-50 data-highlighted:bg-accent data-highlighted:text-accent-foreground [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*='size-'])]:size-4"
                            @select="searchTerm = ''"
                        >
                            <span>{{ item.label }}</span>
                            <ListboxItemIndicator
                                class="ml-auto inline-flex items-center"
                            >
                                <CheckIcon />
                            </ListboxItemIndicator>
                        </ListboxItem>

                        <p
                            v-if="!filteredItems.length"
                            class="px-2 py-1.5 text-sm text-muted-foreground"
                        >
                            Sin resultados.
                        </p>
                    </ListboxContent>
                </PopoverContent>
            </ListboxRoot>
        </Popover>
    </FormField>
</template>
