<script generic="TData, TValue" lang="ts" setup>
import { Paginator } from '@/components/table';
import { CardFooter } from '@/components/ui/card';
import { useVueTable } from '@tanstack/vue-table';

const props = defineProps<{
    table: ReturnType<typeof useVueTable<TData>>;
}>();
</script>

<template>
    <CardFooter>
        <Paginator :table="props.table" />
    </CardFooter>
</template>
