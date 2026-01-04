<script lang="ts" setup>
import { Button } from '@/components/ui/button';
import {
    Pagination,
    PaginationContent,
    PaginationFirst,
    PaginationItem,
    PaginationLast,
    PaginationNext,
    PaginationPrevious,
} from '@/components/ui/pagination';
import { router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps<{
    paginator: {
        links: Array<App.Data.PaginatorLinkData>;
        meta: App.Data.PaginatorMetaData;
    };
}>();

const currentPage = ref(props.paginator.meta.current_page);

watch(
    () => props.paginator.meta.current_page,
    (newPage) => {
        currentPage.value = newPage;
    },
);

function goToPage(newPage: number) {
    const queryParams = new URLSearchParams(window.location.search);
    queryParams.set('page', newPage.toString());

    router.get(
        window.location.pathname + '?' + queryParams.toString(),
        {},
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
}

const isNextorPrevious = (label: string) => {
    return (
        label.includes('previous') ||
        label.includes('next') ||
        label.includes('Previous') ||
        label.includes('Next') ||
        label.includes('anterior') ||
        label.includes('siguiente') ||
        label.includes('Anterior') ||
        label.includes('Siguiente')
    );
};
</script>

<template>
    <Pagination
        v-model:page="currentPage"
        :default-page="currentPage"
        :items-per-page="props.paginator.meta.per_page"
        :sibling-count="1"
        :total="props.paginator.meta.total"
        show-edges
        @update:page="goToPage"
    >
        <PaginationContent
            class="flex flex-wrap items-center justify-center gap-1.5"
        >
            <PaginationFirst class="cursor-pointer" />
            <PaginationPrevious class="cursor-pointer" />

            <template v-for="(item, index) in props.paginator.links">
                <PaginationItem
                    v-if="item.url && !isNextorPrevious(item.label)"
                    :key="index"
                    :value="parseInt(item.label)"
                    as-child
                >
                    <Button
                        :variant="item.active ? 'default' : 'outline'"
                        class="h-10 w-10 cursor-pointer bg-primary p-0 hover:bg-primary/60 hover:text-white"
                    >
                        {{ item.label }}
                    </Button>
                </PaginationItem>
            </template>

            <PaginationNext class="cursor-pointer" />
            <PaginationLast class="cursor-pointer" />
        </PaginationContent>
    </Pagination>
</template>
