<script generic="TData" lang="ts" setup>
import { Button } from '@/components/ui/button';
import {
    Pagination,
    PaginationContent,
    PaginationEllipsis,
    PaginationFirst,
    PaginationItem,
    PaginationLast,
    PaginationNext,
    PaginationPrevious,
} from '@/components/ui/pagination';
import { useVueTable } from '@tanstack/vue-table';
import { computed } from 'vue';

const props = defineProps<{
    table: ReturnType<typeof useVueTable<TData>>;
}>();

const pagination = computed(() => props.table.getState().pagination);
const pageCount = props.table.getPageCount();

const displayedPages = computed<(number | 'ellipsis')[]>(() => {
    const total = pageCount;
    const current = pagination.value.pageIndex + 1;
    const siblings = 1;
    const pages: (number | 'ellipsis')[] = [];

    if (total <= 7) {
        for (let i = 1; i <= total; i++) pages.push(i);
        return pages;
    }

    const firstPage = 1;
    const lastPage = total;

    const left = Math.max(current - siblings, 2);
    const right = Math.min(current + siblings, total - 1);

    const hasLeftEllipsis = left > 2;
    const hasRightEllipsis = right < total - 1;

    pages.push(firstPage);

    if (hasLeftEllipsis) {
        pages.push('ellipsis');
    } else {
        for (let i = 2; i < left; i++) pages.push(i);
    }

    // Middle pages
    for (let i = left; i <= right; i++) {
        pages.push(i);
    }

    if (hasRightEllipsis) {
        pages.push('ellipsis');
    } else {
        for (let i = right + 1; i < lastPage; i++) pages.push(i);
    }

    pages.push(lastPage);

    return pages;
});
</script>

<template>
    <Pagination
        :items-per-page="pagination.pageSize"
        :page="pagination.pageIndex + 1"
        :sibling-count="1"
        :total="props.table.getRowCount()"
        show-edges
        @update:page="(page) => props.table.setPageIndex(page - 1)"
    >
        <PaginationContent
            class="flex flex-wrap items-center justify-center gap-1.5"
        >
            <PaginationFirst size="sm" @click="props.table.setPageIndex(0)" />
            <PaginationPrevious
                :disabled="!props.table.getCanPreviousPage()"
                size="sm"
                @click="props.table.previousPage()"
            />

            <template v-for="(page, index) in displayedPages" :key="index">
                <template v-if="page === 'ellipsis'">
                    <PaginationEllipsis />
                </template>

                <PaginationItem v-else :value="page" as-child>
                    <Button
                        :variant="
                            page === pagination.pageIndex + 1
                                ? 'default'
                                : 'outline'
                        "
                        class="hover:bg-primary/60 hover:text-white"
                        size="sm"
                        @click="props.table.setPageIndex(page - 1)"
                    >
                        {{ page }}
                    </Button>
                </PaginationItem>
            </template>

            <PaginationNext
                :disabled="!props.table.getCanNextPage()"
                size="sm"
                @click="props.table.nextPage()"
            />
            <PaginationLast
                size="sm"
                @click="props.table.setPageIndex(pageCount - 1)"
            />
        </PaginationContent>
    </Pagination>
</template>
