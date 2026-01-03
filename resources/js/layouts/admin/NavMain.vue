<script lang="ts" setup>
import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from '@/components/ui/collapsible';
import {
    SidebarGroup,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
} from '@/components/ui/sidebar';
import { type NavLink } from '@/lib/navLinks';
import { Link, usePage } from '@inertiajs/vue3';
import { ChevronRight } from 'lucide-vue-next';

defineProps<{
    items: NavLink[];
}>();

const page = usePage();
</script>

<template>
    <SidebarGroup>
        <SidebarMenu>
            <template v-for="item in items" :key="item.name">
                <SidebarMenuItem v-if="!item.items">
                    <SidebarMenuButton
                        :isActive="
                            page.component.startsWith(
                                item.componentRoot as string,
                            )
                        "
                        asChild
                    >
                        <Link :href="item.url" prefetch>
                            <component :is="item.icon" />
                            <span>{{ item.name }}</span>
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>

                <Collapsible
                    v-else
                    :default-open="item.isOpen"
                    as-child
                    class="group/collapsible"
                >
                    <SidebarMenuItem>
                        <CollapsibleTrigger as-child>
                            <SidebarMenuButton :tooltip="item.name">
                                <component :is="item.icon" v-if="item.icon" />
                                <span>{{ item.name }}</span>
                                <ChevronRight
                                    class="ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90"
                                />
                            </SidebarMenuButton>
                        </CollapsibleTrigger>
                        <CollapsibleContent>
                            <SidebarMenuSub>
                                <SidebarMenuSubItem
                                    v-for="subItem in item.items"
                                    :key="subItem.name"
                                >
                                    <SidebarMenuSubButton
                                        :isActive="
                                            page.component.startsWith(
                                                subItem.componentRoot as string,
                                            )
                                        "
                                        asChild
                                    >
                                        <Link :href="subItem.url" prefetch>
                                            <span>{{ subItem.name }}</span>
                                        </Link>
                                    </SidebarMenuSubButton>
                                </SidebarMenuSubItem>
                            </SidebarMenuSub>
                        </CollapsibleContent>
                    </SidebarMenuItem>
                </Collapsible>
            </template>
        </SidebarMenu>
    </SidebarGroup>
</template>
