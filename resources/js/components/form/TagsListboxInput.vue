<script lang="ts" setup>
import { Button } from '@/components/ui/button';
import { Field, FieldDescription, FieldError, FieldLabel } from '@/components/ui/field';
import { Popover, PopoverAnchor, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { TagsInput, TagsInputInput, TagsInputItem, TagsInputItemDelete } from '@/components/ui/tags-input';
import { CheckIcon, ChevronDown } from 'lucide-vue-next';
import { ListboxContent, ListboxFilter, ListboxItem, ListboxItemIndicator, ListboxRoot, useFilter } from 'reka-ui';
import { computed, ref, watch } from 'vue';

interface Props {
    label?: string;
    description?: string;
    placeholder?: string;
    options: {
        id: number | string;
        name: string;
    }[];
    error?: string | undefined;
}

type ID = number;

const props = defineProps<Props>();

const model = defineModel<ID[]>({
    default: [],
});

const searchTerm = ref('');
const open = ref(false);

const { contains } = useFilter({ sensitivity: 'base' });

const filteredOptions = computed(() =>
    searchTerm.value === ''
        ? props.options
        : props.options.filter((option) =>
              contains(option.name, searchTerm.value),
          ),
);

const optionMap = computed(
    () => new Map(props.options.map((o) => [o.id, o.name])),
);

const getLabel = (value: number) => {
    return optionMap.value.get(value) ?? value;
};

watch(searchTerm, (f) => {
    if (f) {
        open.value = true;
    }
});
</script>

<template>
    <Field :data-invalid="!!props.error">
        <FieldLabel v-if="props.label">
            {{ props.label }}
        </FieldLabel>

        <Popover v-model:open="open">
            <ListboxRoot v-model="model" highlight-on-hover multiple>
                <PopoverAnchor>
                    <TagsInput
                        v-slot="{ modelValue: tags }"
                        v-model="model"
                        class="w-full"
                    >
                        <TagsInputItem
                            v-for="item in tags"
                            :key="item"
                            :value="item"
                        >
                            <div
                                class="rounded bg-transparent px-2 py-0.5 text-sm"
                            >
                                {{ getLabel(item) }}
                            </div>
                            <TagsInputItemDelete />
                        </TagsInputItem>

                        <ListboxFilter v-model="searchTerm" as-child>
                            <TagsInputInput
                                :placeholder="
                                    props.placeholder || 'Opciones...'
                                "
                                @keydown.enter.prevent
                                @keydown.down="open = true"
                            />
                        </ListboxFilter>

                        <PopoverTrigger as-child>
                            <Button
                                class="order-last ml-auto self-start"
                                size="icon-sm"
                                variant="ghost"
                            >
                                <ChevronDown class="size-3.5" />
                            </Button>
                        </PopoverTrigger>
                    </TagsInput>
                </PopoverAnchor>

                <PopoverContent
                    class="w-[var(--reka-popper-anchor-width)] p-1"
                    @open-auto-focus.prevent
                >
                    <ListboxContent
                        class="max-h-[300px] scroll-py-1 overflow-x-hidden overflow-y-auto empty:p-1 empty:after:block empty:after:content-['No_options']"
                        tabindex="0"
                    >
                        <ListboxItem
                            v-for="item in filteredOptions"
                            :key="item.id"
                            :value="item.id"
                            class="relative flex cursor-default items-center gap-2 rounded-sm px-2 py-1.5 text-sm outline-hidden select-none data-[disabled]:pointer-events-none data-[disabled]:opacity-50 data-[highlighted]:bg-accent data-[highlighted]:text-accent-foreground [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg:not([class*=\'size-\'])]:size-4 [&_svg:not([class*=\'text-\'])]:text-muted-foreground"
                            @select="
                                () => {
                                    searchTerm = '';
                                }
                            "
                        >
                            <span>{{ item.name }}</span>

                            <ListboxItemIndicator
                                class="ml-auto inline-flex items-center justify-center"
                            >
                                <CheckIcon />
                            </ListboxItemIndicator>
                        </ListboxItem>
                    </ListboxContent>
                </PopoverContent>
            </ListboxRoot>
        </Popover>

        <FieldDescription v-if="props.description">
            {{ props.description }}
        </FieldDescription>

        <FieldError v-if="props.error" :errors="[props.error]" />
    </Field>
</template>
