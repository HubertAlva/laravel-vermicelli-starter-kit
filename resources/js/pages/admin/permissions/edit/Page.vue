<script setup lang="ts">
import { Head, setLayoutProps, useForm } from '@inertiajs/vue3';
import { Trash } from '@lucide/vue';
import FormHeader from '@/components/admin/FormHeader.vue';
import PermissionForm from '@/components/admin/PermissionForm.vue';
import AppTooltip from '@/components/AppTooltip.vue';
import SubmitButton from '@/components/SubmitButton.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { useUnsavedChanges } from '@/composables/useUnsavedChanges';
import { cn, truncateText } from '@/lib/utils';
import permissions from '@/routes/admin/permissions';
import roles from '@/routes/admin/roles';
import { confirmDelete } from '@/table/actions';

const props = defineProps<{
    permission: App.Data.PermissionData;
    roles: string[];
    canDelete: boolean;
}>();

setLayoutProps({
    container: 'small',
    breadcrumbs: [
        { title: 'Roles y permisos', href: roles.index() },
        {
            title: truncateText(props.permission.name, 30),
            href: permissions.edit(props.permission.id),
        },
    ],
});

const form = useForm<App.Data.PermissionFormData>({
    name: props.permission.name,
})
    .withPrecognition(permissions.update(props.permission.id))
    .setValidationTimeout(500);

function submit(): void {
    form.submit({
        preserveScroll: true,
        onSuccess: () => form.defaults(),
    });
}

function remove(): void {
    confirmDelete(permissions.destroy(props.permission.id), {
        message:
            props.roles.length > 0
                ? `¿Eliminar este permiso? Se quitará de ${props.roles.length === 1 ? '1 rol' : `${props.roles.length} roles`}.`
                : '¿Eliminar este permiso?',
    });
}

useUnsavedChanges(() => form.isDirty);
</script>

<template>
    <Head :title="`Editar ${permission.name}`" />

    <form
        :class="cn(form.processing && 'pointer-events-none opacity-50')"
        @submit.prevent="submit"
    >
        <FormHeader :title="truncateText(permission.name, 40)">
            <template #badges>
                <Badge
                    v-for="role in props.roles"
                    :key="role"
                    variant="secondary"
                >
                    {{ role }}
                </Badge>
            </template>

            <template #actions>
                <AppTooltip v-if="canDelete" text="Eliminar">
                    <Button
                        type="button"
                        size="icon"
                        variant="destructive"
                        aria-label="Eliminar permiso"
                        @click="remove"
                    >
                        <Trash />
                    </Button>
                </AppTooltip>

                <SubmitButton :processing="form.processing"
                    >Guardar</SubmitButton
                >
            </template>
        </FormHeader>

        <PermissionForm :form />
    </form>
</template>
