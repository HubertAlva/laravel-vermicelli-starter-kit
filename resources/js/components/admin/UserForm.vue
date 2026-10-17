<script setup lang="ts">
import type { InertiaPrecognitiveForm } from '@inertiajs/vue3';
import { PasswordInput, SelectInput, TextInput } from '@/components/form';
import { Card, CardContent } from '@/components/ui/card';
import { FieldGroup } from '@/components/ui/field';

/**
 * Fields shared by the create and edit user pages.
 */
const {
    form,
    roles,
    isEditing = false,
} = defineProps<{
    form: InertiaPrecognitiveForm<App.Data.UserFormData>;
    roles: { value: string; label: string }[];
    isEditing?: boolean;
}>();
</script>

<template>
    <Card>
        <CardContent>
            <FieldGroup class="grid gap-7 md:grid-cols-2 md:gap-x-4">
                <TextInput
                    id="name"
                    v-model="form.name"
                    label="Nombre"
                    autocomplete="name"
                    :error="form.errors.name"
                    @validate="form.validate('name')"
                />

                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    label="Correo electrónico"
                    autocomplete="email"
                    :error="form.errors.email"
                    @validate="form.validate('email')"
                />

                <SelectInput
                    id="role"
                    v-model="form.role"
                    label="Rol"
                    placeholder="Sin rol"
                    :options="roles"
                    :error="form.errors.role"
                    @validate="form.validate('role')"
                />

                <div class="hidden md:block" />

                <PasswordInput
                    id="password"
                    v-model="form.password"
                    label="Contraseña"
                    autocomplete="new-password"
                    :description="
                        isEditing
                            ? 'Déjala en blanco para mantener la actual.'
                            : undefined
                    "
                    :error="form.errors.password"
                    @validate="form.validate('password')"
                />

                <PasswordInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    label="Confirmar contraseña"
                    autocomplete="new-password"
                    :error="form.errors.password_confirmation"
                    @validate="form.validate('password_confirmation')"
                />
            </FieldGroup>
        </CardContent>
    </Card>
</template>
