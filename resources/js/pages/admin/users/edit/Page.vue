<script lang="ts" setup>
import {
    FormHeaderBase,
    HeaderActions,
    Heading,
    HeadingTitle,
} from '@/components/form-header';
import UserForm from '@/components/form/UserForm.vue';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import { useSoftDelete } from '@/composables/useSoftDelete';
import { useUnsavedChanges } from '@/composables/useUnsavedChanges';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { cn, truncateText } from '@/lib/utils';
import Tooltip from '@/plugins/Tooltip.vue';
import users from '@/routes/admin/users';
import { useForm } from '@inertiajs/vue3';
import { Trash } from 'lucide-vue-next';

const props = defineProps<{
    user: App.Data.UserData;
}>();

const parentTitle = 'Usuarios';
const title = 'Editar usuario ' + props.user.name;
const defaultTitle = props.user.name;
const buttonLabel = 'Guardar';

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
    name: props.user.name,
    email: props.user.email,
    role: props.user.role ?? null,
    password: null,
    password_confirmation: null,
}).withPrecognition(users.update(props.user.id));

const submit = () =>
    form.submit({
        preserveState: false,
    });

const deleteTooltip = 'Eliminar';

const { remove } = useSoftDelete({
    destroy: (id) => users.destroy(id).url,
});

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
                    <Tooltip :text="deleteTooltip">
                        <Button
                            size="icon"
                            type="button"
                            variant="destructive"
                            @click="remove(props.user.id)"
                        >
                            <Trash />
                        </Button>
                    </Tooltip>

                    <Button
                        type="submit"
                        @click="submit"
                        :disabled="form.processing || form.validating"
                    >
                        {{ buttonLabel }}
                        <Spinner v-if="form.validating || form.processing" />
                    </Button>
                </HeaderActions>
            </FormHeaderBase>

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
