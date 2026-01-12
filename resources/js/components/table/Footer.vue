<script generic="TData" lang="ts" setup>
import { Paginator } from '@/components/table';
import { CardFooter } from '@/components/ui/card';
import { BaseProps } from '@/table/types';

const props = defineProps<BaseProps<TData>>();
</script>

<template>
    <CardFooter>
        <Paginator :table="props.table" />
    </CardFooter>
</template>
