<script lang="ts" setup>
import HeadingSmall from '@/components/HeadingSmall.vue';
import TwoFactorRecoveryCodes from '@/components/TwoFactorRecoveryCodes.vue';
import TwoFactorSetupModal from '@/components/TwoFactorSetupModal.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { useTwoFactorAuth } from '@/composables/useTwoFactorAuth';
import UserLayout from '@/layouts/UserLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { disable, enable, show } from '@/routes/two-factor';
import { BreadcrumbItem } from '@/types';
import { Form, Head } from '@inertiajs/vue3';
import { ShieldBan, ShieldCheck } from 'lucide-vue-next';
import { onUnmounted, ref } from 'vue';

interface Props {
    requiresConfirmation?: boolean;
    twoFactorEnabled?: boolean;
}

withDefaults(defineProps<Props>(), {
    requiresConfirmation: false,
    twoFactorEnabled: false,
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Autenticación en dos pasos',
        href: show.url(),
    },
];

const { hasSetupData, clearTwoFactorAuthData } = useTwoFactorAuth();
const showSetupModal = ref<boolean>(false);

onUnmounted(() => {
    clearTwoFactorAuthData();
});
</script>

<template>
    <UserLayout :breadcrumbs="breadcrumbs">
        <Head title="Autenticación en dos pasos" />
        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall
                    description="Administra la configuración de tu autenticación en dos pasos"
                    title="Autenticación en dos pasos"
                />

                <div
                    v-if="!twoFactorEnabled"
                    class="flex flex-col items-start justify-start space-y-4"
                >
                    <Badge variant="destructive">Deshabilitado</Badge>

                    <p class="text-muted-foreground">
                        Al habilitar la autenticación en dos pasos, se te pedirá
                        un pin seguro durante el inicio de sesión. Este pin se
                        puede obtener de una aplicación compatible con TOTP en
                        tu teléfono.
                    </p>

                    <div>
                        <Button
                            v-if="hasSetupData"
                            @click="showSetupModal = true"
                        >
                            <ShieldCheck />Continuar configuración
                        </Button>
                        <Form
                            v-else
                            #default="{ processing }"
                            v-bind="enable.form()"
                            @success="showSetupModal = true"
                        >
                            <Button :disabled="processing" type="submit">
                                <ShieldCheck />Habilitar 2FA</Button
                            ></Form
                        >
                    </div>
                </div>

                <div
                    v-else
                    class="flex flex-col items-start justify-start space-y-4"
                >
                    <Badge variant="default">Habilitado</Badge>

                    <p class="text-muted-foreground">
                        Con la autenticación en dos pasos habilitada, se te
                        pedirá un pin seguro durante el inicio de sesión, que
                        puedes obtener de la aplicación compatible con TOTP en
                        tu teléfono.
                    </p>

                    <TwoFactorRecoveryCodes />

                    <div class="relative inline">
                        <Form #default="{ processing }" v-bind="disable.form()">
                            <Button
                                :disabled="processing"
                                type="submit"
                                variant="destructive"
                            >
                                <ShieldBan />
                                Deshabilitar 2FA
                            </Button>
                        </Form>
                    </div>
                </div>

                <TwoFactorSetupModal
                    v-model:isOpen="showSetupModal"
                    :requiresConfirmation="requiresConfirmation"
                    :twoFactorEnabled="twoFactorEnabled"
                />
            </div>
        </SettingsLayout>
    </UserLayout>
</template>
