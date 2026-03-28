import { DeleteButton } from '@/components/table';
import { destroy } from '@/routes/admin/users';
import { useDeleteAction } from '@/table/composables/useDeleteAction';
import { formatDate } from '@/table/helpers/formatDate';
import { roleBadge } from '@/table/helpers/roleBadge';
import type { ColumnDef } from '@tanstack/vue-table';
import { h } from 'vue';

export const columns: ColumnDef<App.Data.UserData>[] = [
    {
        accessorKey: 'name',
        header: () => h('div', { class: 'text-start' }, 'Nombre'),
        cell: ({ row }) => {
            return h(
                'div',
                { class: 'text-start font-medium' },
                row.getValue('name'),
            );
        },
    },
    {
        accessorKey: 'email',
        header: () => h('div', { class: 'text-start' }, 'Correo electrónico'),
        cell: ({ row }) => {
            return h('div', { class: 'text-start' }, row.getValue('email'));
        },
    },
    {
        accessorKey: 'role',
        header: () => h('div', { class: 'text-start' }, 'Rol'),
        cell: ({ row }) => {
            return roleBadge(row.getValue('role'));
        },
    },
    {
        accessorKey: 'created_at',
        header: () => h('div', { class: 'text-end' }, 'Creado'),
        cell: ({ row }) => {
            const createdAt = String(row.getValue('created_at'));

            return h('div', { class: 'text-end' }, formatDate(createdAt));
        },
    },
    {
        id: 'actions',
        enableHiding: false,
        cell: ({ row }) => {
            const model = row.original;
            const modelId = model.id;
            const destroyUrl = destroy(row.original.id).url;

            const { deleteModel } = useDeleteAction<App.Data.UserData>({
                destroyUrl,
            });

            return h(
                'div',
                { class: 'flex justify-end items-center' },
                h(DeleteButton, {
                    modelId,
                    onDelete: () => deleteModel(model, 'users'),
                }),
            );
        },
    },

    {
        id: 'trashed',
        accessorFn: () => null,
        enableSorting: false,
        enableColumnFilter: true,
        enableHiding: true,
        meta: {
            hidden: true,
        },
    },
];
