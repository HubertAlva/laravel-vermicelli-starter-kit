<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import TablePagination from '@/components/table/TablePagination.vue';

/**
 * Pagination for public listings; keeps the current query string.
 */
const { meta, only } = defineProps<{
    meta: Illuminate.LengthAwarePaginator<number, unknown>['meta'];
    /** Props to reload, e.g. the deferred listing prop. */
    only?: string[];
}>();

function visit(query: Record<string, number>): void {
    const url = new URL(window.location.href);

    Object.entries(query).forEach(([key, value]) =>
        url.searchParams.set(key, String(value)),
    );

    router.get(url.pathname + url.search, {}, { only, preserveScroll: false });
}
</script>

<template>
    <TablePagination
        v-if="meta.last_page > 1"
        :page="meta.current_page"
        :page-count="meta.last_page"
        :total="meta.total"
        :per-page="meta.per_page"
        :show-per-page="false"
        @update:page="(page) => visit({ page })"
    />
</template>
