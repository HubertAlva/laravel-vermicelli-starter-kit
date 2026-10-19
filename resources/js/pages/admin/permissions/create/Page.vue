<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import FormHeader from '@/components/admin/FormHeader.vue';
import PermissionForm from '@/components/admin/PermissionForm.vue';
import SubmitButton from '@/components/SubmitButton.vue';
import { useUnsavedChanges } from '@/composables/useUnsavedChanges';
import { cn, truncateText } from '@/lib/utils';
import permissions from '@/routes/admin/permissions';
import roles from '@/routes/admin/roles';

defineOptions({
    layout: {
        container: 'small',
        breadcrumbs: [
            { title: 'Roles y permisos', href: roles.index() },
            { title: 'Crear permiso', href: permissions.create() },
        ],
    },
});

const form = useForm<App.Data.PermissionFormData>({
    name: '',
})
    .withPrecognition(permissions.store())
    .setValidationTimeout(500);

function submit(): void {
    form.submit();
}

useUnsavedChanges(() => form.isDirty);
</script>

<template>
    <Head title="Crear permiso" />

    <form
        :class="cn(form.processing && 'pointer-events-none opacity-50')"
        @submit.prevent="submit"
    >
        <FormHeader :title="truncateText(form.name || 'Nuevo permiso', 40)">
            <template #actions>
                <SubmitButton :processing="form.processing">Crear</SubmitButton>
            </template>
        </FormHeader>

        <PermissionForm :form />
    </form>
</template>
