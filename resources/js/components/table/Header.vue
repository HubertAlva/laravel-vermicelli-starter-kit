<script generic="TData, TValue" lang="ts" setup>
import { Button } from '@/components/ui/button';
import { CardHeader } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import {
    NativeSelect,
    NativeSelectOption,
} from '@/components/ui/native-select';
import { HeaderProps } from '@/table/types';
import { router } from '@inertiajs/vue3';
import { useFocus } from '@vueuse/core';
import { Search } from 'lucide-vue-next';
import { computed, ref, shallowRef, watch } from 'vue';

const props = defineProps<HeaderProps<TData>>();

const searchInput = ref(props.table.getState().globalFilter ?? '');

function updateSearch(value: string | number) {
    searchInput.value = String(value);
}

function runSearch() {
    props.table.setGlobalFilter(searchInput.value);
}

const toggleTrashed = () => {
    const column = props.table.getColumn('trashed');

    router.flushAll();

    column?.setFilterValue(
        column.getFilterValue() === 'only' ? undefined : 'only',
    );
};

const inputRef = shallowRef();
const { focused } = useFocus(inputRef, { initialValue: true });

watch(
    () => props.table.getState().globalFilter,
    (value) => {
        if (focused.value) return;

        searchInput.value = value ?? '';
    },
);

function getFilterValue(id: string): string {
    return (
        (props.table.getState().columnFilters.find((f) => f.id === id)
            ?.value as string) ?? ''
    );
}

function setFilterValue(id: string, value: string) {
    props.table.setColumnFilters((old) => [
        ...old.filter((f) => f.id !== id),
        {
            id,
            value: value || undefined,
        },
    ]);
}

function filterModel(id: string) {
    return computed({
        get: () => getFilterValue(id),
        set: (value: string) => setFilterValue(id, value),
    });
}
</script>

<template>
    <CardHeader>
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div
                class="flex w-full flex-8/12 flex-wrap items-end justify-start gap-4"
            >
                <div class="flex w-full max-w-3xs items-end gap-2">
                    <div class="relative w-full items-center">
                        <Input
                            ref="inputRef"
                            :model-value="searchInput"
                            class="pl-10"
                            placeholder="Buscar..."
                            type="text"
                            @keyup.enter="runSearch"
                            @update:model-value="updateSearch"
                        />
                        <span
                            class="absolute inset-y-0 inset-s-0 flex items-center justify-center px-2"
                        >
                            <Search class="size-6 text-muted-foreground" />
                        </span>
                    </div>
                    <Button
                        aria-label="Buscar"
                        size="icon"
                        type="button"
                        variant="outline"
                        @click="runSearch"
                    >
                        <Search class="size-4" />
                    </Button>
                </div>

                <div
                    v-for="filter in props.customFilterDefinitions"
                    :key="filter.id"
                    class="space-y-1"
                >
                    <label :for="filter.id" class="block text-sm">
                        {{ filter.label }}
                    </label>
                    <NativeSelect
                        :id="filter.id"
                        v-model="filterModel(filter.id).value"
                        class="block h-9 w-full max-w-full min-w-48"
                    >
                        <NativeSelectOption
                            v-for="option in filter.options"
                            :key="option.value"
                            :value="option.value"
                        >
                            {{ option.label }}
                        </NativeSelectOption>
                    </NativeSelect>
                </div>
            </div>

            <div v-if="props.showTrashedFilter">
                <div class="items-top flex space-x-2">
                    <Checkbox
                        id="only_trashed"
                        :model-value="
                            table.getColumn('trashed')?.getFilterValue() ===
                            'only'
                        "
                        @update:model-value="toggleTrashed"
                    />
                    <label
                        class="text-sm leading-none font-medium peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                        for="only_trashed"
                    >
                        Ver papelera
                    </label>
                </div>
            </div>
        </div>
    </CardHeader>
</template>
