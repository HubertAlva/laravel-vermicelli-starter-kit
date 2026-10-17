import type { ColumnDef } from '@tanstack/vue-table';
import { h } from 'vue';
import { DeleteButton } from '@/components/table';
import users from '@/routes/admin/users';
import { confirmDelete } from '@/table/actions';
import { dateCell, headerCell, roleBadge, textCell } from '@/table/cells';

export const userColumns: ColumnDef<App.Data.UserData>[] = [
    {
        accessorKey: 'name',
        header: () => headerCell('Nombre'),
        cell: ({ row }) => textCell(row.original.name, 'font-medium'),
    },
    {
        accessorKey: 'email',
        header: () => headerCell('Correo electrónico'),
        cell: ({ row }) => textCell(row.original.email),
    },
    {
        accessorKey: 'role',
        header: () => headerCell('Rol'),
        cell: ({ row }) => roleBadge(row.original.role),
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
                row.original.is_locked
                    ? []
                    : h(DeleteButton, {
                          onDelete: () =>
                              confirmDelete(users.destroy(row.original.id), {
                                  message: '¿Eliminar este usuario?',
                                  cacheTag: 'users',
                              }),
                      }),
            ),
    },
];
