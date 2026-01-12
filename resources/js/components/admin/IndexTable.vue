<script generic="TData, TValue" lang="ts" setup>
import { Table, TableBody, TableFooter, TableHeader } from '@/components/table';
import { IndexTableProps, TrashedFilter } from '@/table/types';
import { router } from '@inertiajs/vue3';
import { getCoreRowModel, useVueTable } from '@tanstack/vue-table';

const props = defineProps<IndexTableProps<TData, TValue>>();

const table = useVueTable({
    get data() {
        return props.collection.data;
    },
    get columns() {
        return props.columns;
    },
    getCoreRowModel: getCoreRowModel(),
    manualPagination: true,
    manualFiltering: true,
    pageCount: props.collection.meta.last_page,
    rowCount: props.collection.meta.total,
    autoResetPageIndex: false,
    state: {
        pagination: {
            pageIndex: props.collection.meta.current_page - 1,
            pageSize: props.collection.meta.per_page,
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
        const current = table.getState().pagination;

        const next = typeof updater === 'function' ? updater(current) : updater;

        if (next.pageIndex === current.pageIndex) {
            return;
        }

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
</script>

<template>
    <Table>
        <TableHeader :table="table" />

        <TableBody
            :columns="columns"
            :label="props.label"
            :meta="props.collection.meta"
            :onRowClick="onRowClick"
            :onRowHover="onRowHover"
            :onRowLeave="onRowLeave"
            :table="table"
        />

        <TableFooter :table="table" />
    </Table>
</template>
