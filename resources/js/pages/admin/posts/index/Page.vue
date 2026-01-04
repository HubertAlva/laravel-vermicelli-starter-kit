<script lang="ts" setup>
import { IndexTable } from '@/components/admin';
import { Button } from '@/components/ui/button';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { create, edit, index } from '@/routes/admin/posts';
import { Column, Filters } from '@/types/adminTable';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

type DataType = App.Data.PostData;

const props = defineProps<{
    posts?: {
        data: DataType[];
        links: App.Data.PaginatorLinkData[];
        meta: App.Data.PaginatorMetaData;
    };
}>();

const title = 'Artículos';

const breadcrumb = [
    {
        name: title,
    },
];

const deferredData = 'posts';

const columns = [
    { field: 'name', header: 'Nombre' },
    { field: 'published_at', header: 'Estado' },
    { field: 'created_at', header: 'Creado' },
] satisfies Column<DataType>[];

const url = index().url;
const label = 'artículos';

const initialFilters = ref<Filters>({
    page: 1,
    filter: {
        search: undefined,
        trashed: undefined,
    },
});

const handleRowClick = (row: DataType) => {
    router.get(
        edit(row.id),
        {},
        {
            preserveState: true,
            replace: true,
        },
    );
};
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

            <IndexTable
                :collection="props.posts"
                :columns="columns"
                :deferredData="deferredData"
                :initialFilters="initialFilters"
                :label="label"
                :onRowClick="handleRowClick"
                :url="url"
            />
        </div>
    </AdminLayout>
</template>
