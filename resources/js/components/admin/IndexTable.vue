<script generic="TData, TValue" lang="ts" setup>
import { Table, TableBody, TableFooter, TableHeader } from '@/components/table';
import { Spinner } from '@/components/ui/spinner';
import { IndexTableProps } from '@/table/types';
import { Deferred, router } from '@inertiajs/vue3';
import {
    ColumnFiltersState,
    getCoreRowModel,
    useVueTable,
} from '@tanstack/vue-table';
import { ref, shallowRef, watch } from 'vue';

const props = withDefaults(defineProps<IndexTableProps<TData, TValue>>(), {
    showTrashedFilter: true,
});

const dataRef = shallowRef<TData[]>(props.collection?.data ?? []);

const pagination = ref({
    pageIndex: 0,
    pageSize: props.filters?.per_page ?? props.collection?.meta.per_page ?? 10,
});

function buildFilters(
    globalFilter: string,
    columnFilters: ColumnFiltersState,
): Record<string, string> {
    const filters: Record<string, string> = {};

    if (globalFilter) {
        filters.search = globalFilter;
    }

    columnFilters.forEach((filter) => {
        if (typeof filter.value === 'string' && filter.value !== '') {
            filters[filter.id] = filter.value;
        }
    });

    return filters;
}

const globalFilter = ref(props.filters?.search ?? '');

function createColumnFilters(): ColumnFiltersState {
    return [
        {
            id: 'trashed',
            value: props.filters?.trashed,
        },
        ...Object.entries(props.customFilters ?? {}).map(([id, value]) => ({
            id,
            value,
        })),
    ];
}

const columnFilters = ref(createColumnFilters());

function buildParams(page: number) {
    return {
        page,
        per_page: pagination.value.pageSize,
        filter: buildFilters(globalFilter.value, columnFilters.value),
    };
}

const table = useVueTable({
    data: dataRef,
    get columns() {
        return props.columns;
    },
    getCoreRowModel: getCoreRowModel(),
    manualPagination: true,
    manualFiltering: true,
    autoResetPageIndex: false,
    state: {
        get pagination() {
            return pagination.value;
        },

        get globalFilter() {
            return globalFilter.value;
        },

        get columnFilters() {
            return columnFilters.value;
        },
    },

    initialState: {
        columnVisibility: {
            trashed: false,
        },
    },
    onGlobalFilterChange: (updater) => {
        globalFilter.value =
            typeof updater === 'function'
                ? updater(globalFilter.value)
                : updater;

        router.get(props.url, buildParams(1), {
            preserveScroll: true,
            replace: true,
        });
    },

    onPaginationChange: (updater) => {
        const next =
            typeof updater === 'function' ? updater(pagination.value) : updater;

        if (
            next.pageIndex === pagination.value.pageIndex &&
            next.pageSize === pagination.value.pageSize
        ) {
            return;
        }

        const pageSizeChanged = next.pageSize !== pagination.value.pageSize;
        pagination.value = next;

        router.get(
            props.url,
            buildParams(pageSizeChanged ? 1 : next.pageIndex + 1),
            { preserveScroll: true },
        );
    },

    onColumnFiltersChange: (updater) => {
        columnFilters.value =
            typeof updater === 'function'
                ? updater(columnFilters.value)
                : updater;

        router.get(props.url, buildParams(1), { preserveScroll: true });
    },
});

watch(
    () => props.collection,
    (val) => {
        if (!val) return;

        dataRef.value = val.data;

        pagination.value = {
            pageIndex: val.meta.current_page - 1,
            pageSize: val.meta.per_page,
        };

        table.setOptions((prev) => ({
            ...prev,
            pageCount: val.meta.last_page,
            rowCount: val.meta.total,
        }));
    },
    { immediate: true },
);

watch(
    () => [props.filters?.trashed, props.customFilters],
    () => {
        columnFilters.value = createColumnFilters();
    },
    { immediate: true },
);

watch(
    () => props.filters?.search,
    (value) => {
        globalFilter.value = value ?? '';
    },
    { immediate: true },
);
</script>

<template>
    <Table>
        <TableHeader
            :customFilterDefinitions="props.customFilterDefinitions"
            :showTrashedFilter="props.showTrashedFilter"
            :table="table"
        />

        <Deferred :data="props.deferredData">
            <template #fallback>
                <div class="flex h-48 items-center justify-center">
                    <Spinner class="size-11" />
                </div>
            </template>

            <TableBody
                v-if="props.collection"
                :columns="columns"
                :label="props.label"
                :meta="props.collection.meta"
                :onRowClick="onRowClick"
                :onRowHover="onRowHover"
                :onRowLeave="onRowLeave"
                :table="table"
            />

            <TableFooter :table="table" />
        </Deferred>
    </Table>
</template>
