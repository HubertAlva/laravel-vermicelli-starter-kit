<script lang="ts" setup>
import {
    FormHeaderBase,
    HeaderActions,
    Heading,
    HeadingTitle,
} from '@/components/form-header';
import UserForm from '@/components/form/UserForm.vue';
import { Button } from '@/components/ui/button';
import { useUnsavedChanges } from '@/composables/useUnsavedChanges';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { cn, truncateText } from '@/lib/utils';
import users from '@/routes/admin/users';
import { useForm } from '@inertiajs/vue3';

const parentTitle = 'Usuarios';
const title = 'Crear usuario';
const defaultTitle = 'Nuevo usuario';
const buttonLabel = 'Crear';

const breadcrumb = [
    {
        name: parentTitle,
        href: users.index().url,
    },
    {
        name: title,
    },
];

const form = useForm<App.Data.UserFormData>({
    name: undefined,
    email: undefined,
    role: null,
    password: null,
    password_confirmation: null,
}).withPrecognition('post', users.store().url);

const submit = () => form.submit();

useUnsavedChanges(() => form.isDirty);
</script>

<template>
    <AdminLayout :breadcrumb="breadcrumb" :title="title" container="small">
        <div>
            <FormHeaderBase>
                <Heading>
                    <HeadingTitle>
                        {{
                            truncateText(
                                form.name ? form.name : defaultTitle,
                                35,
                            )
                        }}
                    </HeadingTitle>
                </Heading>

                <HeaderActions>
                    <Button type="submit" @click="submit">
                        {{ buttonLabel }}
                    </Button>
                </HeaderActions>
            </FormHeaderBase>

            <pre>{{ form.processing }}</pre>

            <form
                :class="
                    cn(
                        'grid gap-4',
                        form.processing ? 'pointer-events-none opacity-50' : '',
                    )
                "
                @submit.prevent="submit"
            >
                <UserForm :buttonLabel="buttonLabel" :form="form" />
            </form>
        </div>
    </AdminLayout>
</template>
