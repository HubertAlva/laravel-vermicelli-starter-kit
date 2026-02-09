import { ColumnDef, useVueTable } from '@tanstack/vue-table';

// Trashed filter values
export type TrashedFilter = 'with' | 'only' | undefined;

// Table filters definition
export type Filters = {
    search?: string;
    trashed?: TrashedFilter;
};

// Table Base props
export type BaseProps<TData> = {
    table: ReturnType<typeof useVueTable<TData>>;
};

// Table Body props
export type BodyProps<TData, TValue> = {
    table: ReturnType<typeof useVueTable<TData>>;
    columns: ColumnDef<TData, TValue>[];
    meta: App.Data.PaginatorMetaData;
    label: string;
    onRowClick?: (row: TData) => void;
    onRowHover?: (row: TData) => void;
    onRowLeave?: () => void;
};

// Index Table props
export type IndexTableProps<TData, TValue> = {
    columns: ColumnDef<TData, TValue>[];
    collection?: {
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
    deferredData: string;
};
