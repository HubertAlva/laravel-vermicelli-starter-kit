<script setup lang="ts">
import { Head, setLayoutProps, useForm } from '@inertiajs/vue3';
import { Trash } from '@lucide/vue';
import FormHeader from '@/components/admin/FormHeader.vue';
import UserForm from '@/components/admin/UserForm.vue';
import AppTooltip from '@/components/AppTooltip.vue';
import SubmitButton from '@/components/SubmitButton.vue';
import { Button } from '@/components/ui/button';
import { useUnsavedChanges } from '@/composables/useUnsavedChanges';
import { cn, truncateText } from '@/lib/utils';
import users from '@/routes/admin/users';
import { confirmDelete } from '@/table/actions';

const props = defineProps<{
    user: App.Data.UserData;
    roles: { value: string; label: string }[];
    canDelete: boolean;
}>();

setLayoutProps({
    container: 'fit',
    breadcrumbs: [
        { title: 'Usuarios', href: users.index() },
        {
            title: truncateText(props.user.name, 30),
            href: users.edit(props.user.id),
        },
    ],
});

const form = useForm<App.Data.UserFormData>({
    name: props.user.name,
    email: props.user.email,
    role: props.user.role,
    password: null,
    password_confirmation: null,
})
    .withPrecognition(users.update(props.user.id))
    .setValidationTimeout(500);

function submit(): void {
    form.submit({
        preserveScroll: true,
        invalidateCacheTags: 'users',
        onSuccess: () => {
            form.reset('password', 'password_confirmation');
            form.defaults();
        },
    });
}

function remove(): void {
    confirmDelete(users.destroy(props.user.id), {
        message: '¿Eliminar este usuario?',
        cacheTag: 'users',
    });
}

useUnsavedChanges(() => form.isDirty);
</script>

<template>
    <Head :title="`Editar ${user.name}`" />

    <form
        :class="cn(form.processing && 'pointer-events-none opacity-50')"
        @submit.prevent="submit"
    >
        <FormHeader :title="truncateText(user.name, 40)">
            <template #actions>
                <AppTooltip v-if="canDelete" text="Eliminar">
                    <Button
                        type="button"
                        size="icon"
                        variant="destructive"
                        aria-label="Eliminar usuario"
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

        <UserForm :form :roles is-editing />
    </form>
</template>
