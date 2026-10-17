<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import FormHeader from '@/components/admin/FormHeader.vue';
import UserForm from '@/components/admin/UserForm.vue';
import SubmitButton from '@/components/SubmitButton.vue';
import { useUnsavedChanges } from '@/composables/useUnsavedChanges';
import { cn, truncateText } from '@/lib/utils';
import users from '@/routes/admin/users';

defineOptions({
    layout: {
        container: 'fit',
        breadcrumbs: [
            { title: 'Usuarios', href: users.index() },
            { title: 'Crear usuario', href: users.create() },
        ],
    },
});

defineProps<{
    roles: { value: string; label: string }[];
}>();

const form = useForm<App.Data.UserFormData>({
    name: '',
    email: '',
    role: null,
    password: null,
    password_confirmation: null,
})
    .withPrecognition(users.store())
    .setValidationTimeout(500);

function submit(): void {
    form.submit({ invalidateCacheTags: 'users' });
}

useUnsavedChanges(() => form.isDirty);
</script>

<template>
    <Head title="Crear usuario" />

    <form
        :class="cn(form.processing && 'pointer-events-none opacity-50')"
        @submit.prevent="submit"
    >
        <FormHeader :title="truncateText(form.name || 'Nuevo usuario', 40)">
            <template #actions>
                <SubmitButton :processing="form.processing">Crear</SubmitButton>
            </template>
        </FormHeader>

        <UserForm :form :roles />
    </form>
</template>
