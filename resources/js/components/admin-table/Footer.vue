<script lang="ts" setup>
import Paginator from '@/components/Paginator.vue';
import { CardFooter } from '@/components/ui/card';

const props = defineProps<{
    links: Array<App.Data.PaginatorLinkData>;
    meta: App.Data.PaginatorMetaData;
}>();
</script>

<template>
    <CardFooter>
        <Paginator
            :paginator="{
                links: props.links,
                meta: props.meta,
            }"
        />
    </CardFooter>
</template>
