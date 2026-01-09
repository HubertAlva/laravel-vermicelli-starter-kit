<!--<script generic="T" lang="ts" setup>-->
<!--import {-->
<!--    Table,-->
<!--    TableBody,-->
<!--    TableFooter,-->
<!--    TableHeader,-->
<!--    TableRow,-->
<!--} from '@/components/table';-->
<!--import { Spinner } from '@/components/ui/spinner';-->
<!--import { useTableFilters } from '@/composables/useTable';-->
<!--import { cn } from '@/lib/utils';-->
<!--import { Filters, IndexTableProps } from '@/types/table';-->
<!--import { Deferred } from '@inertiajs/vue3';-->
<!--import { ref } from 'vue';-->

<!--const props = defineProps<IndexTableProps<T>>();-->

<!--const initialFilters = ref<Filters>({-->
<!--    page: 1,-->
<!--    filter: {-->
<!--        search: undefined,-->
<!--        trashed: undefined,-->
<!--    },-->
<!--});-->

<!--const { filters, refresh } = useTableFilters(props.url, initialFilters.value);-->
<!--</script>-->

<!--<template>-->
<!--    <Table>-->
<!--        <TableHeader :initialFilters="filters" @refresh="refresh" />-->

<!--        <Deferred :data="deferredData">-->
<!--            <template #fallback>-->
<!--                <div class="flex min-h-[500px] items-center justify-center">-->
<!--                    <Spinner class="size-8" />-->
<!--                </div>-->
<!--            </template>-->

<!--            <template v-if="props.collection">-->
<!--                <TableBody-->
<!--                    :collection="props.collection"-->
<!--                    :columns="props.columns"-->
<!--                    :label="props.label"-->
<!--                >-->
<!--                    <template #default="{ item }">-->
<!--                        <TableRow-->
<!--                            :class="-->
<!--                                cn(props.onRowClick ? 'cursor-pointer' : '')-->
<!--                            "-->
<!--                            :columns="props.columns"-->
<!--                            :filters="filters"-->
<!--                            :item="item"-->
<!--                            :url="props.url"-->
<!--                            @click="props.onRowClick?.(item)"-->
<!--                            @refresh="refresh"-->
<!--                        />-->
<!--                    </template>-->
<!--                </TableBody>-->

<!--                <TableFooter-->
<!--                    :links="props.collection.links"-->
<!--                    :meta="props.collection.meta"-->
<!--                />-->
<!--            </template>-->
<!--        </Deferred>-->
<!--    </Table>-->
<!--</template>-->
