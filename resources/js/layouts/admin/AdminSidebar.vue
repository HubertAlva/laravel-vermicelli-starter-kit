<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppLogo from '@/components/AppLogo.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarRail,
} from '@/components/ui/sidebar';
import NavMain from '@/layouts/admin/NavMain.vue';
import NavUser from '@/layouts/admin/NavUser.vue';
import { usePermissions } from '@/composables/usePermissions';
import { adminNavLinks } from '@/lib/navLinks';
import admin from '@/routes/admin';
import type { NavItem } from '@/types';

const { can } = usePermissions();

/**
 * Drop links the user has no permission for, and groups left empty.
 */
function visible(items: NavItem[]): NavItem[] {
    return items
        .filter((item) => !item.permission || can(item.permission))
        .map((item) =>
            item.items ? { ...item, items: visible(item.items) } : item,
        )
        .filter((item) => !item.items || item.items.length > 0);
}

const navLinks = computed(() => visible(adminNavLinks));
</script>

<template>
    <Sidebar collapsible="icon">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="admin.dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="navLinks" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>

        <SidebarRail />
    </Sidebar>
</template>
