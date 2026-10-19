<script setup lang="ts">
import { Head, setLayoutProps, useForm } from '@inertiajs/vue3';
import FormHeader from '@/components/admin/FormHeader.vue';
import RoleForm from '@/components/admin/RoleForm.vue';
import SubmitButton from '@/components/SubmitButton.vue';
import { Badge } from '@/components/ui/badge';
import { useUnsavedChanges } from '@/composables/useUnsavedChanges';
import { cn, truncateText } from '@/lib/utils';
import roles from '@/routes/admin/roles';
import type { PermissionGroup } from '@/types';

const props = defineProps<{
    role: App.Data.RoleData;
    permissionGroups: PermissionGroup[];
}>();

setLayoutProps({
    container: 'fit',
    breadcrumbs: [
        { title: 'Roles y permisos', href: roles.index() },
        {
            title: truncateText(props.role.label, 30),
            href: roles.edit(props.role.id),
        },
    ],
});

const form = useForm<App.Data.RoleFormData>({
    name: props.role.name,
    permissions: props.role.permissions,
})
    .withPrecognition(roles.update(props.role.id))
    .setValidationTimeout(500);

function submit(): void {
    form.submit({
        preserveScroll: true,
        onSuccess: () => form.defaults(),
    });
}

useUnsavedChanges(() => form.isDirty);
</script>

<template>
    <Head :title="`Editar ${role.label}`" />

    <form
        :class="cn(form.processing && 'pointer-events-none opacity-50')"
        @submit.prevent="submit"
    >
        <FormHeader :title="truncateText(role.label, 40)">
            <template #badges>
                <Badge variant="secondary">
                    {{ role.users_count }}
                    {{ role.users_count === 1 ? 'usuario' : 'usuarios' }}
                </Badge>
            </template>

            <template #actions>
                <SubmitButton :processing="form.processing"
                    >Guardar</SubmitButton
                >
            </template>
        </FormHeader>

        <RoleForm :form :permission-groups />
    </form>
</template>
