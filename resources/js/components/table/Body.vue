<script generic="T" lang="ts" setup>
import { CardContent } from '@/components/ui/card';
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { BodyProps } from '@/types/table';

const props = defineProps<BodyProps<T>>();

defineSlots<{
    default(props: { item: T; rowIndex: number }): any;
}>();
</script>

<template>
    <CardContent>
        <Table>
            <TableCaption>
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <span>
                        Mostrando {{ props.collection.meta.from }} al
                        {{ props.collection.meta.to }} de
                        {{ props.collection.meta.total }}
                        {{ props.label }}
                    </span>
                    <span>Lista de {{ props.label }}.</span>
                </div>
            </TableCaption>
            <TableHeader>
                <TableRow>
                    <TableHead
                        v-for="(column, index) in props.columns"
                        :key="column.field"
                        :class="
                            index === columns.length - 1 ? 'text-right' : ''
                        "
                    >
                        {{ column.header }}
                    </TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                <template v-if="props.collection.data.length">
                    <template
                        v-for="(item, rowIndex) in collection.data"
                        :key="rowIndex"
                    >
                        <slot :item="item" :rowIndex="rowIndex" />
                    </template>
                </template>
                <template v-else>
                    <TableRow>
                        <TableCell class="text-center" colspan="100%">
                            No se encontraron {{ props.label }}.
                        </TableCell>
                    </TableRow>
                </template>
            </TableBody>
        </Table>
    </CardContent>
</template>
