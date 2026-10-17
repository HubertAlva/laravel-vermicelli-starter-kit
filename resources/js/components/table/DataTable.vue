<script setup lang="ts" generic="TData extends { id: number }">
import { Deferred, router } from '@inertiajs/vue3';
import type { ColumnDef } from '@tanstack/vue-table';
import { FlexRender, getCoreRowModel, useVueTable } from '@tanstack/vue-table';
import { computed } from 'vue';
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
} from '@/components/ui/card';
import { Skeleton } from '@/components/ui/skeleton';
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { cn } from '@/lib/utils';
import type { TableFilterDefinition, TableFilters } from '@/table/types';
import type { Paginated } from '@/types';
import TablePagination from './TablePagination.vue';
import TableToolbar from './TableToolbar.vue';

/**
 * Server-side paginated, searchable and filterable table for admin listings.
 *
 * `data` is expected to be a deferred prop named `prop`; every interaction
 * reloads only that prop and `filters` with the new query string.
 */
const props = withDefaults(
    defineProps<{
        columns: ColumnDef<TData, any>[];
        data?: Paginated<TData>;
        prop: string;
        url: string;
        label: string;
        filters?: TableFilters;
        filterDefinitions?: TableFilterDefinition[];
        trashable?: boolean;
        onRowClick?: (row: TData) => void;
        onRowHover?: (row: TData) => void;
        onRowLeave?: () => void;
    }>(),
    {
        filters: () => ({}),
        filterDefinitions: () => [],
        trashable: false,
    },
);

const meta = computed(() => props.data?.meta);

const columnFilters = computed<Record<string, string | undefined>>(() =>
    Object.fromEntries(
        props.filterDefinitions.map(({ id }) => [
            id,
            props.filters[id] ? String(props.filters[id]) : undefined,
        ]),
    ),
);

const table = useVueTable({
    get data() {
        return props.data?.data ?? [];
    },
    get columns() {
        return props.columns;
    },
    getCoreRowModel: getCoreRowModel(),
    manualPagination: true,
    manualFiltering: true,
});

type Query = {
    page?: number;
    per_page?: number;
    search?: string;
    trashed?: string;
    filters?: Record<string, string | undefined>;
};

function visit(changes: Query): void {
    const search = changes.search ?? props.filters.search ?? undefined;
    const trashed =
        'trashed' in changes
            ? changes.trashed
            : (props.filters.trashed ?? undefined);
    const filters = { ...columnFilters.value, ...changes.filters };

    router.get(
        props.url,
        {
            page: changes.page ?? 1,
            per_page:
                changes.per_page ??
                meta.value?.per_page ??
                props.filters.per_page,
            filter: Object.fromEntries(
                Object.entries({ search, trashed, ...filters }).filter(
                    ([, value]) =>
                        value !== undefined && value !== null && value !== '',
                ),
            ),
        },
        {
            only: [props.prop, 'filters'],
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
}
</script>

<template>
    <Card>
        <CardHeader>
            <TableToolbar
                :search="filters.search ?? ''"
                :filters="columnFilters"
                :filter-definitions="filterDefinitions"
                :trashable
                :trashed="filters.trashed === 'only'"
                @search="(search) => visit({ search })"
                @filter="(id, value) => visit({ filters: { [id]: value } })"
                @update:trashed="
                    (value) => visit({ trashed: value ? 'only' : undefined })
                "
            />
        </CardHeader>

        <Deferred :data="prop">
            <template #fallback>
                <CardContent class="space-y-3">
                    <Skeleton v-for="n in 5" :key="n" class="h-10 w-full" />
                </CardContent>
            </template>

            <CardContent>
                <Table>
                    <TableCaption v-if="meta?.total">
                        Mostrando {{ meta.from }} al {{ meta.to }} de
                        {{ meta.total }} {{ label }}.
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
                                    :render="header.column.columnDef.header"
                                    :props="header.getContext()"
                                />
                            </TableHead>
                        </TableRow>
                    </TableHeader>

                    <TableBody>
                        <template v-if="table.getRowModel().rows.length">
                            <TableRow
                                v-for="row in table.getRowModel().rows"
                                :key="row.id"
                                :class="cn(onRowClick && 'cursor-pointer')"
                                @click="onRowClick?.(row.original)"
                                @mouseenter="onRowHover?.(row.original)"
                                @mouseleave="onRowLeave?.()"
                            >
                                <TableCell
                                    v-for="cell in row.getVisibleCells()"
                                    :key="cell.id"
                                >
                                    <FlexRender
                                        :render="cell.column.columnDef.cell"
                                        :props="cell.getContext()"
                                    />
                                </TableCell>
                            </TableRow>
                        </template>

                        <TableRow v-else>
                            <TableCell
                                :colspan="columns.length"
                                class="h-24 text-center"
                            >
                                No se encontraron {{ label }}.
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </CardContent>

            <CardFooter v-if="meta && meta.total > 0">
                <TablePagination
                    :page="meta.current_page"
                    :page-count="meta.last_page"
                    :total="meta.total"
                    :per-page="meta.per_page"
                    @update:page="(page) => visit({ page })"
                    @update:per-page="(per_page) => visit({ per_page })"
                />
            </CardFooter>
        </Deferred>
    </Card>
</template>
