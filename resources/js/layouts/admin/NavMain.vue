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
import { computed } from 'vue';

const props = defineProps<{
    items: NavLink[];
}>();

const page = usePage();

function navKey(item: NavLink) {
    return (item.componentRoot || item.url || item.name) as string;
}

const activeMap = computed<Record<string, boolean>>(() => {
    const map: Record<string, boolean> = {};

    for (const item of props.items) {
        const itemKey = navKey(item);

        if (item.items) {
            let parentActive = false;

            for (const subItem of item.items) {
                const subKey = navKey(subItem);

                const isSubActive = page.component.startsWith(
                    subItem.componentRoot as string,
                );

                map[subKey] = isSubActive;

                if (isSubActive) {
                    parentActive = true;
                }
            }

            map[itemKey] = parentActive;
        } else {
            map[itemKey] = page.component.startsWith(
                item.componentRoot as string,
            );
        }
    }

    return map;
});

const openMap = computed<Record<string, boolean>>(() => {
    const map: Record<string, boolean> = {};

    for (const item of props.items) {
        if (!item.items) continue;

        const itemKey = navKey(item);
        map[itemKey] = activeMap.value[itemKey];
    }

    return map;
});
</script>

<template>
    <SidebarGroup>
        <SidebarMenu>
            <template v-for="item in props.items" :key="item.name">
                <SidebarMenuItem v-if="!item.items">
                    <SidebarMenuButton
                        :isActive="activeMap[navKey(item)]"
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
                    :default-open="openMap[navKey(item)]"
                    as-child
                    class="group/collapsible"
                >
                    <SidebarMenuItem>
                        <CollapsibleTrigger as-child>
                            <SidebarMenuButton
                                :isActive="activeMap[navKey(item)]"
                                :tooltip="item.name"
                            >
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
                                        :isActive="activeMap[navKey(subItem)]"
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
