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
