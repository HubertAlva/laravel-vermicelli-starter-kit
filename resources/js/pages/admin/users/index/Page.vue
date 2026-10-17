<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { DataTable } from '@/components/table';
import { Button } from '@/components/ui/button';
import { usePermissions } from '@/composables/usePermissions';
import { useRowPrefetch } from '@/composables/useRowPrefetch';
import users from '@/routes/admin/users';
import { userColumns } from '@/table/columns/userColumns';
import type { TableFilterDefinition, TableFilters } from '@/table/types';
import type { Paginated } from '@/types';

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Usuarios', href: users.index() }],
    },
});

const props = defineProps<{
    users?: Paginated<App.Data.UserData>;
    filters: TableFilters;
    roles: { value: string; label: string }[];
}>();

const { can } = usePermissions();

const filterDefinitions = computed<TableFilterDefinition[]>(() => [
    { id: 'role', label: 'Rol', options: props.roles },
]);

const rowPrefetch = useRowPrefetch((row) => users.edit(row.id), 'users');
</script>

<template>
    <Head title="Usuarios" />

    <div class="space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h1 class="text-2xl font-semibold">Usuarios</h1>

            <Button v-if="can('users.create')" as-child>
                <Link :href="users.create()">Nuevo usuario</Link>
            </Button>
        </div>

        <DataTable
            :columns="userColumns"
            :data="props.users"
            prop="users"
            :url="users.index().url"
            label="usuarios"
            :filters
            :filter-definitions="filterDefinitions"
            :on-row-click="rowPrefetch.onClick"
            :on-row-hover="rowPrefetch.onHover"
            :on-row-leave="rowPrefetch.onLeave"
        />
    </div>
</template>
