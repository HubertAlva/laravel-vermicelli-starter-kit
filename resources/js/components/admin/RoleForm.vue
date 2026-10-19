<script setup lang="ts">
import type { InertiaPrecognitiveForm } from '@inertiajs/vue3';
import { CheckboxInput, TextInput } from '@/components/form';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { FieldError, FieldGroup } from '@/components/ui/field';
import type { PermissionGroup } from '@/types';

/**
 * Fields shared by the create and edit role pages.
 */
const { form, permissionGroups } = defineProps<{
    form: InertiaPrecognitiveForm<App.Data.RoleFormData>;
    permissionGroups: PermissionGroup[];
}>();

function togglePermission(permission: string, granted: boolean): void {
    form.permissions = granted
        ? [...form.permissions, permission]
        : form.permissions.filter((name) => name !== permission);
}
</script>

<template>
    <div class="space-y-4">
        <Card>
            <CardContent>
                <FieldGroup>
                    <TextInput
                        id="name"
                        v-model="form.name"
                        label="Nombre"
                        description="En minúsculas, sin espacios (p. ej. editor)."
                        :error="form.errors.name"
                        @validate="form.validate('name')"
                    />
                </FieldGroup>
            </CardContent>
        </Card>

        <Card>
            <CardHeader>
                <CardTitle>Permisos</CardTitle>
                <FieldError
                    v-if="form.errors.permissions"
                    :errors="[form.errors.permissions]"
                />
            </CardHeader>
            <CardContent class="grid gap-6 md:grid-cols-2">
                <fieldset
                    v-for="group in permissionGroups"
                    :key="group.resource"
                    class="space-y-3"
                >
                    <legend class="mb-3 font-semibold">
                        {{ group.resource }}
                    </legend>

                    <CheckboxInput
                        v-for="permission in group.permissions"
                        :id="`permission-${permission.id}`"
                        :key="permission.id"
                        :label="permission.name"
                        :model-value="
                            form.permissions.includes(permission.name)
                        "
                        @update:model-value="
                            togglePermission(permission.name, $event)
                        "
                    />
                </fieldset>

                <p
                    v-if="permissionGroups.length === 0"
                    class="text-sm text-muted-foreground"
                >
                    No hay permisos registrados.
                </p>
            </CardContent>
        </Card>
    </div>
</template>
