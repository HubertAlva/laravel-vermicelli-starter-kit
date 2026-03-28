<script lang="ts" setup>
import { IndexTable } from '@/components/admin';
import { Button } from '@/components/ui/button';
import { usePrefetch } from '@/composables/usePrefetch';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { create, edit, index } from '@/routes/admin/users';
import { columns } from '@/table/columns/userColumns';
import { Link, usePage } from '@inertiajs/vue3';

type DataType = App.Data.UserData;

type Filters = {
    search?: string;
};

const props = defineProps<{
    users?: {
        data: DataType[];
        links: App.Data.PaginatorLinkData[];
        meta: App.Data.PaginatorMetaData;
    };
    filters?: Filters;
}>();

const page = usePage();

const title = 'Usuarios';

const breadcrumb = [
    {
        name: title,
    },
];

const deferredData = 'users';
const url = index().url;
const label = 'usuarios';

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
                        <Link :href="create().url">Nuevo usuario</Link>
                    </Button>
                </div>
            </div>

            <IndexTable
                :collection="props.users"
                :columns="columns"
                :deferredData="deferredData"
                :filters="props.filters"
                :label="label"
                :onRowClick="onClick"
                :onRowHover="onHover"
                :onRowLeave="onLeave"
                :url="url"
                :showTrashedFilter="false"
            />
        </div>
    </AdminLayout>
</template>
