export type TableFilterOption = {
    value: string;
    label: string;
};

/**
 * A select filter shown in the table toolbar, sent as `filter[id]`.
 */
export type TableFilterDefinition = {
    id: string;
    label: string;
    options: TableFilterOption[];
};

/**
 * Current listing state, as returned by the server in the `filters` prop.
 */
export type TableFilters = {
    search?: string | null;
    trashed?: string | null;
    per_page?: number;
    [filter: string]: string | number | null | undefined;
};
