<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import FormHeader from '@/components/admin/FormHeader.vue';
import RoleForm from '@/components/admin/RoleForm.vue';
import SubmitButton from '@/components/SubmitButton.vue';
import { useUnsavedChanges } from '@/composables/useUnsavedChanges';
import { cn, truncateText } from '@/lib/utils';
import roles from '@/routes/admin/roles';
import type { PermissionGroup } from '@/types';

defineOptions({
    layout: {
        container: 'fit',
        breadcrumbs: [
            { title: 'Roles y permisos', href: roles.index() },
            { title: 'Crear rol', href: roles.create() },
        ],
    },
});

defineProps<{
    permissionGroups: PermissionGroup[];
}>();

const form = useForm<App.Data.RoleFormData>({
    name: '',
    permissions: [],
})
    .withPrecognition(roles.store())
    .setValidationTimeout(500);

function submit(): void {
    form.submit();
}

useUnsavedChanges(() => form.isDirty);
</script>

<template>
    <Head title="Crear rol" />

    <form
        :class="cn(form.processing && 'pointer-events-none opacity-50')"
        @submit.prevent="submit"
    >
        <FormHeader :title="truncateText(form.name || 'Nuevo rol', 40)">
            <template #actions>
                <SubmitButton :processing="form.processing">Crear</SubmitButton>
            </template>
        </FormHeader>

        <RoleForm :form :permission-groups />
    </form>
</template>
