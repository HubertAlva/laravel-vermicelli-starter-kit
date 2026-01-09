<script lang="ts" setup>
import { CustomTable } from '@/components/table';
import { columns } from '@/components/table/columns';
import { Button } from '@/components/ui/button';
import { usePrefetch } from '@/composables/usePrefetch';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { create, edit, index } from '@/routes/admin/posts';
import { Link, usePage } from '@inertiajs/vue3';

type DataType = App.Data.PostData;

type TrashedFilter = 'with' | 'only' | undefined;

type Filters = {
    search?: string;
    trashed?: TrashedFilter;
};

const props = defineProps<{
    posts: {
        data: DataType[];
        links: App.Data.PaginatorLinkData[];
        meta: App.Data.PaginatorMetaData;
    };
    filters?: Filters;
}>();

const page = usePage();

const title = 'Artículos';

const breadcrumb = [
    {
        name: title,
    },
];

const deferredData = 'posts';

const url = index().url;
const label = 'artículos';

const { onClick, onHover, onLeave } = usePrefetch<DataType>(
    (row) => edit(row.id),
    {
        delay: 75,
        successId: page.flash.toast?.id ?? null,
    },
);
</script>

<template>
    <AdminLayout :breadcrumb="breadcrumb" :title="title">
        <div class="space-y-3">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <h1 class="text-2xl font-semibold">{{ title }}</h1>

                <div class="flex items-center justify-end gap-2">
                    <Button asChild>
                        <Link :href="create().url">Nuevo artículo</Link>
                    </Button>
                </div>
            </div>

            <CustomTable
                :collection="props.posts"
                :columns="columns"
                :filters="props.filters"
                :label="label"
                :onRowClick="onClick"
                :onRowHover="onHover"
                :onRowLeave="onLeave"
                :url="url"
            />
        </div>
    </AdminLayout>
</template>
