<script generic="TData, TValue" lang="ts" setup>
import { Paginator, TableHeader as Header } from '@/components/table';
import { Card, CardContent, CardFooter } from '@/components/ui/card';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { router } from '@inertiajs/vue3';
import type { ColumnDef } from '@tanstack/vue-table';
import { FlexRender, getCoreRowModel, useVueTable } from '@tanstack/vue-table';

type TrashedFilter = 'with' | 'only' | undefined;

type Filters = {
    search?: string;
    trashed?: TrashedFilter;
};

const props = defineProps<{
    columns: ColumnDef<TData, TValue>[];
    collection: {
        data: TData[];
        links: App.Data.PaginatorLinkData[];
        meta: App.Data.PaginatorMetaData;
    };
    url: string;
    label: string;
    filters?: Filters;
    onRowClick?: (row: TData) => void;
    onRowHover?: (row: TData) => void;
    onRowLeave?: () => void;
}>();

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
    autoResetPageIndex: true,

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
                replace: true,
                preserveScroll: true,
            },
        );
    },
});
</script>

<template>
    <Card>
        <Header :table="table" />

        <CardContent>
            <Table>
                <TableCaption>
                    <div
                        class="flex flex-wrap items-center justify-between gap-3"
                    >
                        <span>
                            Mostrando {{ props.collection.meta.from }} al
                            {{ props.collection.meta.to }} de
                            {{ props.collection.meta.total }}
                            {{ props.label }}
                        </span>
                        <span>Lista de {{ props.label }}.</span>
                    </div>
                </TableCaption>

                <TableHeader>
                    <TableRow
                        v-for="headerGroup in table.getHeaderGroups()"
                        :key="headerGroup.id"
                    >
                        <TableHead
                            v-for="header in headerGroup.headers"
                            :key="header.id"
                        >
                            <FlexRender
                                v-if="!header.isPlaceholder"
                                :props="header.getContext()"
                                :render="header.column.columnDef.header"
                            />
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <template v-if="table.getRowModel().rows?.length">
                        <TableRow
                            v-for="row in table.getRowModel().rows"
                            :key="row.id"
                            :data-state="
                                row.getIsSelected() ? 'selected' : undefined
                            "
                            @click="props.onRowClick?.(row.original)"
                            @mouseleave="props.onRowLeave?.()"
                            @mouseover="props.onRowHover?.(row.original)"
                        >
                            <TableCell
                                v-for="cell in row.getVisibleCells()"
                                :key="cell.id"
                            >
                                <FlexRender
                                    :props="cell.getContext()"
                                    :render="cell.column.columnDef.cell"
                                />
                            </TableCell>
                        </TableRow>
                    </template>
                    <template v-else>
                        <TableRow>
                            <TableCell
                                :colspan="columns.length"
                                class="h-24 text-center"
                            >
                                No se encontraron {{ props.label }}.
                            </TableCell>
                        </TableRow>
                    </template>
                </TableBody>
            </Table>
        </CardContent>

        <CardFooter>
            <Paginator :table="table" />
        </CardFooter>
    </Card>
</template>
