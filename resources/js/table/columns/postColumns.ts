import type { ColumnDef } from '@tanstack/vue-table';
import { h } from 'vue';
import { DeleteButton } from '@/components/table';
import { Badge } from '@/components/ui/badge';
import posts from '@/routes/admin/posts';
import { confirmDelete } from '@/table/actions';
import { dateCell, headerCell, statusBadge } from '@/table/cells';

export const postColumns: ColumnDef<App.Data.PostData>[] = [
    {
        accessorKey: 'name',
        header: () => headerCell('Nombre'),
        cell: ({ row }) =>
            h('div', { class: 'flex items-center gap-2 font-medium' }, [
                row.original.name,
                row.original.deleted_at
                    ? h(Badge, { variant: 'destructive' }, () => 'En papelera')
                    : null,
            ]),
    },
    {
        accessorKey: 'published_at',
        header: () => headerCell('Estado'),
        cell: ({ row }) =>
            statusBadge(!!row.original.published_at, {
                active: 'Publicado',
                inactive: 'Borrador',
            }),
    },
    {
        accessorKey: 'created_at',
        header: () => headerCell('Creado', 'end'),
        cell: ({ row }) => dateCell(row.original.created_at),
    },
    {
        id: 'actions',
        cell: ({ row }) =>
            h(
                'div',
                { class: 'flex justify-end' },
                h(DeleteButton, {
                    label: row.original.deleted_at
                        ? 'Eliminar definitivamente'
                        : 'Mover a la papelera',
                    onDelete: () =>
                        row.original.deleted_at
                            ? confirmDelete(
                                  posts.forceDestroy(row.original.id),
                                  {
                                      message:
                                          'El artículo se eliminará definitivamente. ¿Continuar?',
                                      cacheTag: 'posts',
                                  },
                              )
                            : confirmDelete(posts.destroy(row.original.id), {
                                  message: '¿Mover el artículo a la papelera?',
                                  cacheTag: 'posts',
                              }),
                }),
            ),
    },
];
