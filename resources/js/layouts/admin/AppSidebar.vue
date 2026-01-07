<script lang="ts" setup>
import type { SidebarProps } from '@/components/ui/sidebar';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarRail,
} from '@/components/ui/sidebar';
import AdditionalLinks from './AdditionalLinks.vue';
import NavMain from './NavMain.vue';
import NavUser from './NavUser.vue';

import AppearanceTabs from '@/components/AppearanceTabs.vue';
import { adminNavLinks } from '@/lib/navLinks';
import admin from '@/routes/admin';
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen } from 'lucide-vue-next';

const props = withDefaults(defineProps<SidebarProps>(), {
    collapsible: 'icon',
});

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const page = usePage();

const data = {
    user: {
        name: page.props.auth.user.name,
        email: page.props.auth.user.email,
        avatar: '/images/logo.png',
    },
    additionals: [
        {
            name: 'Documentación',
            url: '#',
            icon: BookOpen,
        },
    ],
};
</script>

<template>
    <Sidebar v-bind="props">
        <SidebarHeader>
            <Link
                :href="admin.dashboard().url"
                class="flex items-center justify-start gap-2 p-2 group-data-[collapsible=icon]:size-8 group-data-[collapsible=icon]:p-0"
            >
                <div
                    class="flex size-8 items-center justify-center rounded-lg border bg-white"
                >
                    <img alt="CTS" class="h-5" src="/images/logo.png" />
                </div>
                <div
                    class="grid flex-1 text-left text-sm leading-tight group-data-[collapsible=icon]:hidden"
                >
                    <span class="truncate font-medium">{{ appName }}</span>
                </div>
            </Link>
        </SidebarHeader>
        <SidebarContent>
            <NavMain :items="adminNavLinks" />
            <AdditionalLinks :additionals="data.additionals" />
        </SidebarContent>
        <SidebarFooter>
            <div>
                <AppearanceTabs mode="select" />
            </div>
            <NavUser :user="data.user" />
        </SidebarFooter>
        <SidebarRail />
    </Sidebar>
</template>
