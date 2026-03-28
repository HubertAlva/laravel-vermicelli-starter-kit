<script lang="ts" setup>
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { Link } from '@inertiajs/vue3';
import { type LucideIcon } from 'lucide-vue-next';

defineProps<{
    additionals: {
        name: string;
        url: string;
        icon: LucideIcon;
    }[];
}>();
</script>

<template>
    <SidebarGroup class="group-data-[collapsible=icon]:hidden">
        <SidebarGroupLabel>Más información</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem v-for="item in additionals" :key="item.name">
                <SidebarMenuButton asChild>
                    <Link :href="item.url">
                        <component :is="item.icon" />
                        <span>{{ item.name }}</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
