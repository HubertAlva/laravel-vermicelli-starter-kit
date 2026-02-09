<script lang="ts" setup>
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import { edit } from '@/routes/profile';
import { send } from '@/routes/verification';
import { Form, Head, Link, usePage } from '@inertiajs/vue3';

import DeleteUser from '@/components/DeleteUser.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import UserLayout from '@/layouts/UserLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem } from '@/types';

interface Props {
    mustVerifyEmail: boolean;
    status?: string;
}

defineProps<Props>();

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Configuración de perfil',
        href: edit().url,
    },
];

const page = usePage();
const user = page.props.auth.user;
</script>

<template>
    <UserLayout :breadcrumbs="breadcrumbItems">
        <Head title="Configuración de perfil" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall
                    description="Actualiza tu nombre y dirección de correo electrónico"
                    title="Profile information"
                />

                <Form
                    v-slot="{ errors, processing, recentlySuccessful }"
                    class="space-y-6"
                    v-bind="ProfileController.update.form()"
                >
                    <div class="grid gap-2">
                        <Label for="name">Nombre</Label>
                        <Input
                            id="name"
                            :default-value="user.name"
                            autocomplete="name"
                            class="mt-1 block w-full"
                            name="name"
                            placeholder="Full name"
                            required
                        />
                        <InputError :message="errors.name" class="mt-2" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="email">Correo electrónico</Label>
                        <Input
                            id="email"
                            :default-value="user.email"
                            autocomplete="username"
                            class="mt-1 block w-full"
                            name="email"
                            placeholder="Email address"
                            required
                            type="email"
                        />
                        <InputError :message="errors.email" class="mt-2" />
                    </div>

                    <div v-if="mustVerifyEmail && !user.email_verified_at">
                        <p class="-mt-4 text-sm text-muted-foreground">
                            Tu dirección de correo electrónico no está
                            verificada.
                            <Link
                                :href="send()"
                                as="button"
                                class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500"
                            >
                                Haz clic aquí para reenviar el correo de
                                verificación.
                            </Link>
                        </p>

                        <div
                            v-if="status === 'verification-link-sent'"
                            class="mt-2 text-sm font-medium text-green-600"
                        >
                            Se ha enviado un nuevo enlace de verificación a tu
                            dirección de correo electrónico.
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <Button
                            :disabled="processing"
                            data-test="update-profile-button"
                        >
                            Guardar
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

            <DeleteUser />
        </SettingsLayout>
    </UserLayout>
</template>
