<script setup lang="ts">
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import {
    NativeSelect,
    NativeSelectOption,
} from '@/components/ui/native-select';
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

const {
    page,
    pageCount,
    total,
    perPage,
    perPageOptions = [10, 30, 50, 100],
    showPerPage = true,
} = defineProps<{
    page: number;
    pageCount: number;
    total: number;
    perPage: number;
    perPageOptions?: number[];
    showPerPage?: boolean;
}>();

const emit = defineEmits<{
    'update:page': [page: number];
    'update:perPage': [perPage: number];
}>();

/**
 * Pages to render: always the first and last, the current page with one
 * sibling on each side, and ellipses for the gaps.
 */
const pages = computed<(number | 'ellipsis')[]>(() => {
    if (pageCount <= 7) {
        return Array.from({ length: pageCount }, (_, index) => index + 1);
    }

    const left = Math.max(page - 1, 2);
    const right = Math.min(page + 1, pageCount - 1);
    const middle = Array.from(
        { length: right - left + 1 },
        (_, index) => left + index,
    );

    return [
        1,
        ...(left > 2 ? ['ellipsis' as const] : []),
        ...middle,
        ...(right < pageCount - 1 ? ['ellipsis' as const] : []),
        pageCount,
    ];
});

function goTo(target: number): void {
    if (target >= 1 && target <= pageCount && target !== page) {
        emit('update:page', target);
    }
}
</script>

<template>
    <div class="flex w-full flex-wrap items-center justify-between gap-4">
        <Pagination
            :page
            :total
            :items-per-page="perPage"
            :sibling-count="1"
            show-edges
            class="mx-0 w-auto"
        >
            <PaginationContent class="flex-wrap gap-1.5">
                <PaginationFirst
                    size="sm"
                    :disabled="page <= 1"
                    @click="goTo(1)"
                />
                <PaginationPrevious
                    size="sm"
                    :disabled="page <= 1"
                    @click="goTo(page - 1)"
                />

                <template v-for="(item, index) in pages" :key="index">
                    <PaginationEllipsis v-if="item === 'ellipsis'" />
                    <PaginationItem v-else :value="item" as-child>
                        <Button
                            size="sm"
                            :variant="item === page ? 'default' : 'outline'"
                            @click="goTo(item)"
                        >
                            {{ item }}
                        </Button>
                    </PaginationItem>
                </template>

                <PaginationNext
                    size="sm"
                    :disabled="page >= pageCount"
                    @click="goTo(page + 1)"
                />
                <PaginationLast
                    size="sm"
                    :disabled="page >= pageCount"
                    @click="goTo(pageCount)"
                />
            </PaginationContent>
        </Pagination>

        <label
            v-if="showPerPage"
            class="flex items-center gap-2 text-sm text-muted-foreground"
        >
            Por página
            <NativeSelect
                :model-value="perPage"
                class="h-9 min-w-20"
                @update:model-value="
                    (value) => emit('update:perPage', Number(value))
                "
            >
                <NativeSelectOption
                    v-for="option in perPageOptions"
                    :key="option"
                    :value="option"
                >
                    {{ option }}
                </NativeSelectOption>
            </NativeSelect>
        </label>
    </div>
</template>
