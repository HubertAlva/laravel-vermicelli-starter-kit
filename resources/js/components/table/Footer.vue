<script generic="TData" lang="ts" setup>
import { Paginator } from '@/components/table';
import { CardFooter } from '@/components/ui/card';
import {
    NativeSelect,
    NativeSelectOption,
} from '@/components/ui/native-select';
import { BaseProps } from '@/table/types';
import { AcceptableValue } from 'reka-ui';

const props = defineProps<BaseProps<TData>>();

const pageSizes = [
    {
        value: 10,
        label: '10',
    },
    {
        value: 30,
        label: '30',
    },
    {
        value: 40,
        label: '40',
    },
    {
        value: 50,
        label: '50',
    },
];

function onPageSizeChange(value: AcceptableValue | AcceptableValue[]) {
    props.table.setPageSize(Number(value));
}
</script>

<template>
    <CardFooter class="flex-wrap gap-4 lg:flex-nowrap">
        <Paginator :table="props.table" />

        <NativeSelect
            id="pageSize"
            :model-value="props.table.getState().pagination.pageSize"
            class="block h-9 w-full max-w-full min-w-24"
            @update:modelValue="onPageSizeChange"
        >
            <NativeSelectOption
                v-for="option in pageSizes"
                :key="option.value"
                :value="option.value"
            >
                {{ option.label }}
            </NativeSelectOption>
        </NativeSelect>
    </CardFooter>
</template>
