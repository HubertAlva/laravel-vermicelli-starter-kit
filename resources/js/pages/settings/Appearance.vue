<script lang="ts" setup>
import { Head } from '@inertiajs/vue3';

import AppearanceTabs from '@/components/AppearanceTabs.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { type BreadcrumbItem } from '@/types';

import UserLayout from '@/layouts/UserLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { edit } from '@/routes/appearance';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Configuración de apariencia',
        href: edit().url,
    },
];
</script>

<template>
    <UserLayout :breadcrumbs="breadcrumbItems">
        <Head title="Configuración de apariencia" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall
                    description="Administra la configuración de apariencia de tu cuenta"
                    title="Configuración de apariencia"
                />
                <AppearanceTabs />
            </div>
        </SettingsLayout>
    </UserLayout>
</template>
