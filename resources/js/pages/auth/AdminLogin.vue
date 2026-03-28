<script lang="ts" setup>
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { store } from '@/routes/login';
import { request } from '@/routes/password';
import { Form, Head } from '@inertiajs/vue3';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();
</script>

<template>
    <AuthBase
        description="Introduce tu correo y contraseña para iniciar sesión"
        title="Iniciar sesión"
    >
        <Head title="Iniciar sesión" />

        <div
            v-if="status"
            class="mb-4 text-center text-sm font-medium text-green-600"
        >
            {{ status }}
        </div>

        <Form
            v-slot="{ errors, processing }"
            :reset-on-success="['password']"
            class="flex flex-col gap-6"
            v-bind="store.form()"
        >
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="email">Correo electrónico</Label>
                    <Input
                        id="email"
                        :tabindex="1"
                        autocomplete="email"
                        autofocus
                        name="email"
                        placeholder="email@ejemplo.com"
                        required
                        type="email"
                    />
                    <InputError :message="errors.email" />
                </div>

                <div class="grid gap-2">
                    <div class="flex items-center justify-between">
                        <Label for="password">Contraseña</Label>
                        <TextLink
                            v-if="canResetPassword"
                            :href="request()"
                            :tabindex="5"
                            class="text-sm"
                        >
                            ¿Olvidaste tu contraseña?
                        </TextLink>
                    </div>
                    <Input
                        id="password"
                        :tabindex="2"
                        autocomplete="current-password"
                        name="password"
                        placeholder="Contraseña"
                        required
                        type="password"
                    />
                    <InputError :message="errors.password" />
                </div>

                <div class="flex items-center justify-between">
                    <Label class="flex items-center space-x-3" for="remember">
                        <Checkbox id="remember" :tabindex="3" name="remember" />
                        <span> Recordarme </span>
                    </Label>
                </div>

                <Button
                    :disabled="processing"
                    :tabindex="4"
                    class="mt-4 w-full"
                    data-test="login-button"
                    type="submit"
                >
                    <Spinner v-if="processing" />
                    Iniciar sesión
                </Button>
            </div>
        </Form>
    </AuthBase>
</template>
