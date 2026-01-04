export interface AdminRow {
    id: number;
    deleted_at?: string | null;
}

// Column render variants your table understands
export type ColumnType =
    | 'text'
    | 'image'
    | 'images'
    | 'fileSize'
    | 'boolean'
    | 'custom';

// Generic column definition
export type Column<T> = {
    /**
     * Field from the model OR a virtual / nested path
     * Examples:
     *  - 'name'
     *  - 'published_at'
     *  - 'order.type'
     */
    field: keyof T | string;

    /** Column header label */
    header: string;

    /** Optional rendering hint */
    type?: ColumnType;
};

// Table filters definition
export type Filters = {
    page: number;
    filter: {
        search: string | undefined;
        trashed: 'only' | 'with' | undefined;
    };
};

// Table Header props
export type HeaderProps = {
    allowSoftDelete?: boolean;
    initialFilters: Filters;
};

// Table Body props
export type BodyProps<T> = {
    collection: {
        data: T[];
        links: App.Data.PaginatorLinkData[];
        meta: App.Data.PaginatorMetaData;
    };
    columns: Array<{ field: string; header: string; type?: string }>;
    label: string;
};

// Table Row props
export type RowProps<T> = {
    item: T;
    columns: Column<T>[];
    url: string;
    allowSoftDelete?: boolean;
    allowActions?: boolean;
    filters: Filters;
};

// Table Cell content props
export type CellProps<T> = {
    column: Column<T>;
    row: T;
};

// Index Table props
export type IndexTableProps<T> = {
    collection?: {
        data: T[];
        links: App.Data.PaginatorLinkData[];
        meta: App.Data.PaginatorMetaData;
    };
    columns: Column<T>[];
    url: string;
    label: string;
    deferredData: string;
    onRowClick?: (row: T) => void;
};
