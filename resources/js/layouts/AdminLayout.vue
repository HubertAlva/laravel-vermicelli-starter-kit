<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import AppToaster from '@/components/AppToaster.vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import ThemeSwitcher from '@/components/ThemeSwitcher.vue';
import { Separator } from '@/components/ui/separator';
import {
    SidebarInset,
    SidebarProvider,
    SidebarTrigger,
} from '@/components/ui/sidebar';
import AdminSidebar from '@/layouts/admin/AdminSidebar.vue';
import { cn } from '@/lib/utils';
import type { BreadcrumbItem } from '@/types';

/**
 * Set from a page with `defineOptions({ layout: { breadcrumbs, container } })`
 * or dynamically with `setLayoutProps()`.
 */
const { breadcrumbs = [], container = 'full' } = defineProps<{
    breadcrumbs?: BreadcrumbItem[];
    container?: 'full' | 'fit' | 'small';
}>();

const page = usePage();
</script>

<template>
    <SidebarProvider :default-open="page.props.sidebarOpen">
        <AdminSidebar />

        <SidebarInset class="min-w-0">
            <header
                class="flex h-16 shrink-0 items-center justify-between gap-2 border-b px-4 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12"
            >
                <div class="flex min-w-0 items-center gap-2">
                    <SidebarTrigger class="-ml-1" />
                    <Separator
                        orientation="vertical"
                        class="mr-2 data-[orientation=vertical]:h-4"
                    />
                    <Breadcrumbs
                        v-if="breadcrumbs.length"
                        :breadcrumbs="breadcrumbs"
                    />
                </div>

                <ThemeSwitcher />
            </header>

            <div
                class="flex flex-1 justify-center bg-muted p-4 dark:bg-neutral-900"
            >
                <div
                    :class="
                        cn('w-full', {
                            'max-w-5xl': container === 'fit',
                            'max-w-2xl': container === 'small',
                        })
                    "
                >
                    <slot />
                </div>
            </div>
        </SidebarInset>

        <AppToaster />
    </SidebarProvider>
</template>
