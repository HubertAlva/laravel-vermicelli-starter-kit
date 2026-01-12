<script generic="TData, TValue" lang="ts" setup>
import { Table, TableBody, TableFooter, TableHeader } from '@/components/table';
import { Spinner } from '@/components/ui/spinner';
import { IndexTableProps, TrashedFilter } from '@/table/types';
import { Deferred, router } from '@inertiajs/vue3';
import { getCoreRowModel, useVueTable } from '@tanstack/vue-table';
import { ref, shallowRef, watch } from 'vue';

const props = defineProps<IndexTableProps<TData, TValue>>();

const dataRef = shallowRef<TData[]>(props.collection?.data ?? []);

const pagination = ref({
    pageIndex: 0,
    pageSize: 10,
});

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

        globalFilter: props.filters?.search ?? '',

        columnFilters: [
            {
                id: 'trashed',
                value: props.filters?.trashed as TrashedFilter,
            },
        ],
    },

    initialState: {
        columnVisibility: {
            trashed: false,
        },
    },
    onGlobalFilterChange: (updater) => {
        const current = table.getState().globalFilter;

        const next = typeof updater === 'function' ? updater(current) : updater;

        const trashed = table
            .getState()
            .columnFilters.find((f) => f.id === 'trashed')
            ?.value as TrashedFilter;

        router.get(
            props.url,
            {
                page: 1,
                filter: {
                    search: next || undefined,
                    trashed: trashed || undefined,
                },
            },
            {
                preserveScroll: true,
                replace: true,
            },
        );
    },

    onPaginationChange: (updater) => {
        const next =
            typeof updater === 'function' ? updater(pagination.value) : updater;

        if (next.pageIndex === pagination.value.pageIndex) {
            return;
        }

        pagination.value = next;

        const trashed = table
            .getState()
            .columnFilters.find((f) => f.id === 'trashed')
            ?.value as TrashedFilter;

        router.get(
            props.url,
            {
                page: next.pageIndex + 1,
                filter: {
                    search: table.getState().globalFilter || undefined,
                    trashed: trashed || undefined,
                },
            },
            {
                preserveScroll: true,
            },
        );
    },

    onColumnFiltersChange: (updater) => {
        const current = table.getState().columnFilters;

        const next = typeof updater === 'function' ? updater(current) : updater;

        const trashed = next.find((f) => f.id === 'trashed')
            ?.value as TrashedFilter;

        router.get(
            props.url,
            {
                page: 1,
                filter: {
                    search: table.getState().globalFilter || undefined,
                    trashed,
                },
            },
            {
                preserveScroll: true,
            },
        );
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
</script>

<template>
    <Table>
        <TableHeader :table="table" />

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
