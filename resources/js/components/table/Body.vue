<script generic="TData, TValue" lang="ts" setup>
import { CardContent } from '@/components/ui/card';
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import type { ColumnDef } from '@tanstack/vue-table';
import { FlexRender, useVueTable } from '@tanstack/vue-table';

type TrashedFilter = 'with' | 'only' | undefined;

type Filters = {
    search?: string;
    trashed?: TrashedFilter;
};

const props = defineProps<{
    table: ReturnType<typeof useVueTable<TData>>;
    columns: ColumnDef<TData, TValue>[];
    links?: App.Data.PaginatorLinkData[];
    meta?: App.Data.PaginatorMetaData;
    url: string;
    label: string;
    filters?: Filters;
    onRowClick?: (row: TData) => void;
    onRowHover?: (row: TData) => void;
    onRowLeave?: () => void;
}>();
</script>

<template>
    <CardContent>
        <Table>
            <TableCaption>
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <span>
                        Mostrando {{ props.meta?.from }} al
                        {{ props.meta?.to }} de
                        {{ props.meta?.total }}
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
</template>
