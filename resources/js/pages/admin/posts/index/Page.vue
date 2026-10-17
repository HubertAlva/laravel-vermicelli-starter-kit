<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { DataTable } from '@/components/table';
import { Button } from '@/components/ui/button';
import { usePermissions } from '@/composables/usePermissions';
import { useRowPrefetch } from '@/composables/useRowPrefetch';
import posts from '@/routes/admin/posts';
import { postColumns } from '@/table/columns/postColumns';
import type { TableFilters } from '@/table/types';
import type { Paginated } from '@/types';

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Artículos', href: posts.index() }],
    },
});

defineProps<{
    posts?: Paginated<App.Data.PostData>;
    filters: TableFilters;
}>();

const { can } = usePermissions();

const rowPrefetch = useRowPrefetch((row) => posts.edit(row.id), 'posts');
</script>

<template>
    <Head title="Artículos" />

    <div class="space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h1 class="text-2xl font-semibold">Artículos</h1>

            <Button v-if="can('posts.create')" as-child>
                <Link :href="posts.create()">Nuevo artículo</Link>
            </Button>
        </div>

        <DataTable
            :columns="postColumns"
            :data="$props.posts"
            prop="posts"
            :url="posts.index().url"
            label="artículos"
            :filters
            :trashable="can('posts.restore')"
            :on-row-click="rowPrefetch.onClick"
            :on-row-hover="rowPrefetch.onHover"
            :on-row-leave="rowPrefetch.onLeave"
        />
    </div>
</template>
