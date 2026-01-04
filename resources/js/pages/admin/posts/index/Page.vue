<script lang="ts" setup>
import {
    Table,
    TableBody,
    TableFooter,
    TableHeader,
    TableRow,
} from '@/components/admin-table';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { create, edit } from '@/routes/admin/posts';
import { Column } from '@/types/adminTable';
import { Deferred, Link, router } from '@inertiajs/vue3';

const props = defineProps<{
    posts?: {
        data: Array<App.Data.PostData>;
        links: Array<App.Data.PaginatorLinkData>;
        meta: App.Data.PaginatorMetaData;
    };
}>();

const title = 'Artículos';

const breadcrumb = [
    {
        name: title,
    },
];

const columns = [
    { field: 'name', header: 'Nombre' },
    { field: 'published_at', header: 'Estado' },
    { field: 'created_at', header: 'Creado' },
] satisfies Column<App.Data.PostData>[];

const target = '/admin/posts';
const label = 'artículos';

const handleRowClick = (row: App.Data.PostData) => {
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
            <div class="flex items-center justify-between gap-4">
                <h1>{{ title }}</h1>

                <Button>
                    <Link :href="create().url"> Nuevo artículo </Link>
                </Button>
            </div>

            <Table>
                <TableHeader :target="target" />

                <Deferred data="posts">
                    <template #fallback>
                        <div
                            class="flex min-h-[660px] items-center justify-center"
                        >
                            <Spinner class="size-8" />
                        </div>
                    </template>

                    <template v-if="props.posts">
                        <TableBody
                            :columns="columns"
                            :data="props.posts"
                            :label="label"
                        >
                            <template #default="{ item }">
                                <TableRow
                                    :columns="columns"
                                    :item="item"
                                    :target="target"
                                    @click="handleRowClick(item)"
                                />
                            </template>
                        </TableBody>

                        <TableFooter
                            :links="props.posts.links"
                            :meta="props.posts.meta"
                        />
                    </template>
                </Deferred>
            </Table>
        </div>
    </AdminLayout>
</template>
