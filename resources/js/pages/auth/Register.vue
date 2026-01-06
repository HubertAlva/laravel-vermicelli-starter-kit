<script lang="ts" setup>
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { home, login } from '@/routes';
import { store } from '@/routes/register';
import { Form, Head } from '@inertiajs/vue3';
</script>

<template>
    <AuthBase
        description="Ingrese sus datos personales para crear una cuenta"
        title="Crear una cuenta"
    >
        <Head title="Registrar" />

        <Form
            v-slot="{ errors, processing }"
            :reset-on-success="['password', 'password_confirmation']"
            class="flex flex-col gap-6"
            v-bind="store.form()"
        >
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="name">Nombre</Label>
                    <Input
                        id="name"
                        :tabindex="1"
                        autocomplete="name"
                        autofocus
                        name="name"
                        placeholder="Nombre completo"
                        required
                        type="text"
                    />
                    <InputError :message="errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">Correo electrónico</Label>
                    <Input
                        id="email"
                        :tabindex="2"
                        autocomplete="email"
                        name="email"
                        placeholder="email@ejemplo.com"
                        required
                        type="email"
                    />
                    <InputError :message="errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">Contraseña</Label>
                    <Input
                        id="password"
                        :tabindex="3"
                        autocomplete="new-password"
                        name="password"
                        placeholder="Contraseña"
                        required
                        type="password"
                    />
                    <InputError :message="errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation"
                        >Confirmar contraseña</Label
                    >
                    <Input
                        id="password_confirmation"
                        :tabindex="4"
                        autocomplete="new-password"
                        name="password_confirmation"
                        placeholder="Confirmar contraseña"
                        required
                        type="password"
                    />
                    <InputError :message="errors.password_confirmation" />
                </div>

                <Button
                    :disabled="processing"
                    class="mt-2 w-full"
                    data-test="register-user-button"
                    tabindex="5"
                    type="submit"
                >
                    <Spinner v-if="processing" />
                    Crear cuenta
                </Button>
            </div>

            <div class="text-center text-sm text-muted-foreground">
                ¿Ya tienes una cuenta?
                <TextLink
                    :href="login()"
                    :tabindex="6"
                    class="underline underline-offset-4"
                >
                    Iniciar sesión
                </TextLink>
            </div>

            <div class="text-center text-sm text-muted-foreground">
                Regresar al
                <TextLink :href="home()" :tabindex="6">inicio</TextLink>
            </div>
        </Form>
    </AuthBase>
</template>
