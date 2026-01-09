<script generic="TData, TValue" lang="ts" setup>
import { CardHeader } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox'; // const { allowSoftDelete = true, initialFilters } = defineProps<HeaderProps>();
import { Input } from '@/components/ui/input';
import { useVueTable } from '@tanstack/vue-table';
import { debounce } from 'lodash';
import { Search } from 'lucide-vue-next';

const props = defineProps<{
    table: ReturnType<typeof useVueTable<TData>>;
}>();

const debouncedSearch = debounce((value: string) => {
    props.table.setGlobalFilter(value);
}, 300);

function updateSearch(value: string | number) {
    debouncedSearch(String(value));
}

const toggleTrashed = () => {
    const column = props.table.getColumn('trashed');

    column?.setFilterValue(
        column.getFilterValue() === 'only' ? undefined : 'only',
    );
};
</script>

<template>
    <CardHeader>
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="relative w-full max-w-3xs items-center">
                <Input
                    :model-value="props.table.getState().globalFilter ?? ''"
                    class="pl-10"
                    placeholder="Buscar..."
                    type="text"
                    @update:model-value="updateSearch"
                />
                <span
                    class="absolute inset-y-0 start-0 flex items-center justify-center px-2"
                >
                    <Search class="size-6 text-muted-foreground" />
                </span>
            </div>

            <div>
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
