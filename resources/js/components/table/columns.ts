import { useDeleteAction } from '@/components/table/composables/useDeleteAction';
import DeleteButton from '@/components/table/DeleteButton.vue';
import { formatDate } from '@/components/table/helpers/formatDate';
import { statusBadge } from '@/components/table/helpers/statusBadge';
import { destroy, softDelete } from '@/routes/admin/posts';
import type { ColumnDef } from '@tanstack/vue-table';
import { h } from 'vue';

export const columns: ColumnDef<App.Data.PostData>[] = [
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
        accessorKey: 'published_at',
        header: () => h('div', { class: 'text-start' }, 'Estado'),
        cell: ({ row }) => {
            return statusBadge(row.getValue('published_at'), {
                active: 'Publicado',
                inactive: 'Borrador',
            });
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
            const softDeleteUrl = softDelete(row.original.id).url;

            const { deleteModel } = useDeleteAction<App.Data.PostData>({
                destroyUrl,
                softDeleteUrl,
            });

            return h(
                'div',
                { class: 'flex justify-end items-center' },
                h(DeleteButton, {
                    modelId,
                    onDelete: () => deleteModel(model),
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
