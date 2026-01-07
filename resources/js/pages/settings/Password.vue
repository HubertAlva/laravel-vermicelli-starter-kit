<script lang="ts" setup>
import PasswordController from '@/actions/App/Http/Controllers/Settings/PasswordController';
import InputError from '@/components/InputError.vue';
import UserLayout from '@/layouts/UserLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { edit } from '@/routes/user-password';
import { Form, Head } from '@inertiajs/vue3';

import HeadingSmall from '@/components/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { type BreadcrumbItem } from '@/types';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Configuración de contraseña',
        href: edit().url,
    },
];
</script>

<template>
    <UserLayout :breadcrumbs="breadcrumbItems">
        <Head title="Configuración de contraseña" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall
                    description="Asegúrate de que tu cuenta esté utilizando una contraseña larga y aleatoria para mantenerla segura"
                    title="Actualiza tu contraseña"
                />

                <Form
                    v-slot="{ errors, processing, recentlySuccessful }"
                    :options="{
                        preserveScroll: true,
                    }"
                    :reset-on-error="[
                        'password',
                        'password_confirmation',
                        'current_password',
                    ]"
                    class="space-y-6"
                    reset-on-success
                    v-bind="PasswordController.update.form()"
                >
                    <div class="grid gap-2">
                        <Label for="current_password">Contraseña actual</Label>
                        <Input
                            id="current_password"
                            autocomplete="current-password"
                            class="mt-1 block w-full"
                            name="current_password"
                            placeholder="Contraseña actual"
                            type="password"
                        />
                        <InputError :message="errors.current_password" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="password">Nueva contraseña</Label>
                        <Input
                            id="password"
                            autocomplete="new-password"
                            class="mt-1 block w-full"
                            name="password"
                            placeholder="Nueva contraseña"
                            type="password"
                        />
                        <InputError :message="errors.password" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="password_confirmation">
                            Confirmar contraseña
                        </Label>
                        <Input
                            id="password_confirmation"
                            autocomplete="new-password"
                            class="mt-1 block w-full"
                            name="password_confirmation"
                            placeholder="Confirmar contraseña"
                            type="password"
                        />
                        <InputError :message="errors.password_confirmation" />
                    </div>

                    <div class="flex items-center gap-4">
                        <Button
                            :disabled="processing"
                            data-test="update-password-button"
                        >
                            Guardar contraseña
                        </Button>

                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p
                                v-show="recentlySuccessful"
                                class="text-sm text-neutral-600"
                            >
                                Guardado.
                            </p>
                        </Transition>
                    </div>
                </Form>
            </div>
        </SettingsLayout>
    </UserLayout>
</template>
