<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Check, Lock, Minus, Pencil } from '@lucide/vue';
import AppTooltip from '@/components/AppTooltip.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardAction,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { usePermissions } from '@/composables/usePermissions';
import permissions from '@/routes/admin/permissions';
import roles from '@/routes/admin/roles';
import type { PermissionGroup } from '@/types';

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Roles y permisos', href: roles.index() }],
    },
});

const props = defineProps<{
    roles: App.Data.RoleData[];
    permissionGroups: PermissionGroup[];
}>();

const { can } = usePermissions();

function hasPermission(role: App.Data.RoleData, permission: string): boolean {
    return role.permissions.includes(permission);
}
</script>

<template>
    <Head title="Roles y permisos" />

    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h1 class="text-2xl font-semibold">Roles y permisos</h1>

            <div class="flex flex-wrap gap-2">
                <Button
                    v-if="can('permissions.create')"
                    variant="outline"
                    as-child
                >
                    <Link :href="permissions.create()">Nuevo permiso</Link>
                </Button>

                <Button v-if="can('roles.create')" as-child>
                    <Link :href="roles.create()">Nuevo rol</Link>
                </Button>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <Card v-for="role in props.roles" :key="role.id">
                <CardHeader>
                    <CardTitle>{{ role.label }}</CardTitle>
                    <CardDescription>
                        {{ role.users_count }}
                        {{ role.users_count === 1 ? 'usuario' : 'usuarios' }}
                        · {{ role.permissions.length }}
                        {{
                            role.permissions.length === 1
                                ? 'permiso'
                                : 'permisos'
                        }}
                    </CardDescription>
                    <CardAction>
                        <AppTooltip
                            v-if="role.is_protected"
                            text="Este rol no se puede editar"
                        >
                            <Lock
                                class="size-4 text-muted-foreground"
                                aria-label="Rol protegido"
                            />
                        </AppTooltip>
                        <AppTooltip
                            v-else-if="can('roles.update')"
                            text="Editar"
                        >
                            <Button
                                size="icon"
                                variant="ghost"
                                aria-label="Editar rol"
                                as-child
                            >
                                <Link :href="roles.edit(role.id)">
                                    <Pencil />
                                </Link>
                            </Button>
                        </AppTooltip>
                    </CardAction>
                </CardHeader>
            </Card>

            <p
                v-if="props.roles.length === 0"
                class="text-sm text-muted-foreground"
            >
                No hay roles registrados.
            </p>
        </div>

        <Card>
            <CardContent>
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Permiso</TableHead>
                            <TableHead
                                v-for="role in props.roles"
                                :key="role.id"
                                class="text-center"
                            >
                                {{ role.label }}
                            </TableHead>
                        </TableRow>
                    </TableHeader>

                    <TableBody>
                        <template
                            v-for="group in props.permissionGroups"
                            :key="group.resource"
                        >
                            <TableRow class="bg-muted/50 hover:bg-muted/50">
                                <TableCell
                                    :colspan="props.roles.length + 1"
                                    class="font-semibold"
                                >
                                    {{ group.resource }}
                                </TableCell>
                            </TableRow>

                            <TableRow
                                v-for="permission in group.permissions"
                                :key="permission.id"
                            >
                                <TableCell>
                                    <div class="flex items-center gap-2">
                                        <Badge
                                            variant="outline"
                                            class="font-mono"
                                        >
                                            {{ permission.name }}
                                        </Badge>

                                        <template v-if="!permission.is_system">
                                            <Badge variant="secondary">
                                                Personalizado
                                            </Badge>

                                            <AppTooltip
                                                v-if="can('permissions.update')"
                                                text="Editar"
                                            >
                                                <Button
                                                    size="icon-sm"
                                                    variant="ghost"
                                                    aria-label="Editar permiso"
                                                    as-child
                                                >
                                                    <Link
                                                        :href="
                                                            permissions.edit(
                                                                permission.id,
                                                            )
                                                        "
                                                    >
                                                        <Pencil />
                                                    </Link>
                                                </Button>
                                            </AppTooltip>
                                        </template>
                                    </div>
                                </TableCell>
                                <TableCell
                                    v-for="role in props.roles"
                                    :key="role.id"
                                    class="text-center"
                                >
                                    <Check
                                        v-if="
                                            hasPermission(role, permission.name)
                                        "
                                        class="mx-auto size-4 text-green-600 dark:text-green-500"
                                        aria-label="Concedido"
                                    />
                                    <Minus
                                        v-else
                                        class="mx-auto size-4 text-muted-foreground"
                                        aria-label="No concedido"
                                    />
                                </TableCell>
                            </TableRow>
                        </template>

                        <TableRow v-if="props.permissionGroups.length === 0">
                            <TableCell
                                :colspan="props.roles.length + 1"
                                class="text-center text-muted-foreground"
                            >
                                No hay permisos registrados.
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </CardContent>
        </Card>
    </div>
</template>
