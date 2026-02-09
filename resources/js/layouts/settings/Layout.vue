<script lang="ts" setup>
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { toUrl } from '@/lib/utils';
import { edit as editAppearance } from '@/routes/appearance';
import { edit as editProfile } from '@/routes/profile';
import { show } from '@/routes/two-factor';
import { edit as editPassword } from '@/routes/user-password';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';

const sidebarNavItems: NavItem[] = [
    {
        title: 'Perfil',
        href: editProfile(),
        componentRoot: 'settings/Profile',
    },
    {
        title: 'Contraseña',
        href: editPassword(),
        componentRoot: 'settings/Password',
    },
    {
        title: 'Autenticación en dos pasos',
        href: show(),
        componentRoot: 'settings/TwoFactor',
    },
    {
        title: 'Apariencia',
        href: editAppearance(),
        componentRoot: 'settings/Appearance',
    },
];

const page = usePage();
</script>

<template>
    <div class="px-4 py-6">
        <Heading
            description="Administra la configuración de tu perfil y cuenta"
            title="Configuración"
        />

        <div class="flex flex-col lg:flex-row lg:space-x-8">
            <aside class="w-full max-w-xl lg:w-54">
                <nav class="flex flex-col space-y-1 space-x-0">
                    <Button
                        v-for="item in sidebarNavItems"
                        :key="toUrl(item.href)"
                        :class="[
                            'w-full justify-start',
                            {
                                'bg-muted': page.component.startsWith(
                                    item.componentRoot || '',
                                ),
                            },
                        ]"
                        as-child
                        variant="ghost"
                    >
                        <Link :href="item.href">
                            <component :is="item.icon" class="h-4 w-4" />
                            {{ item.title }}
                        </Link>
                    </Button>
                </nav>
            </aside>

            <Separator class="my-6 lg:hidden" />

            <div class="flex-1 md:max-w-2xl">
                <section class="max-w-xl space-y-12">
                    <slot />
                </section>
            </div>
        </div>
    </div>
</template>
