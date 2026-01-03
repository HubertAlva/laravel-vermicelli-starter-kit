<script lang="ts" setup>
import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from '@/components/ui/breadcrumb';
import { Separator } from '@/components/ui/separator';
import {
    SidebarInset,
    SidebarProvider,
    SidebarTrigger,
} from '@/components/ui/sidebar';
import AppSidebar from '@/layouts/admin/AppSidebar.vue';
import { truncateText } from '@/lib/utils';
import Notifications from '@/plugins/Notifications.vue';
import admin from '@/routes/admin';
import { Head, Link } from '@inertiajs/vue3';

interface Props {
    title: string;
    breadcrumb: Array<{
        name: string;
        href?: string;
    }>;
    container?: 'full' | 'fit' | 'small';
}

const { title, breadcrumb, container = 'full' } = defineProps<Props>();

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
</script>

<template>
    <Head>
        <title>{{ title }}</title>
    </Head>

    <SidebarProvider>
        <AppSidebar />
        <SidebarInset>
            <header
                class="flex h-16 shrink-0 items-center gap-2 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12"
            >
                <div class="flex items-center gap-2 px-4">
                    <SidebarTrigger class="-ml-1" />
                    <Separator
                        class="mr-2 data-[orientation=vertical]:h-4"
                        orientation="vertical"
                    />
                    <Breadcrumb>
                        <BreadcrumbList>
                            <BreadcrumbItem class="hidden md:block">
                                <BreadcrumbLink as-child>
                                    <Link :href="admin.dashboard().url">
                                        {{ appName }}
                                    </Link>
                                </BreadcrumbLink>
                            </BreadcrumbItem>
                            <BreadcrumbSeparator class="hidden md:block" />
                            <template
                                v-for="(item, index) in breadcrumb"
                                :key="index"
                            >
                                <BreadcrumbItem
                                    v-if="item.href"
                                    class="hidden md:block"
                                >
                                    <BreadcrumbLink as-child>
                                        <Link :href="item.href">
                                            {{ item.name }}
                                        </Link>
                                    </BreadcrumbLink>
                                </BreadcrumbItem>

                                <BreadcrumbSeparator
                                    v-if="item.href"
                                    class="hidden md:block"
                                />

                                <BreadcrumbItem v-else>
                                    <BreadcrumbPage>
                                        {{ truncateText(item.name, 20) }}
                                    </BreadcrumbPage>
                                </BreadcrumbItem>
                            </template>
                        </BreadcrumbList>
                    </Breadcrumb>
                </div>
            </header>
            <div
                class="flex flex-1 justify-center bg-muted p-4 dark:bg-neutral-900"
            >
                <div
                    :class="[
                        'w-full',
                        container === 'full' ? 'max-w-full' : '',
                        container === 'fit' ? 'max-w-5xl' : '',
                        container === 'small' ? 'max-w-2xl' : '',
                    ]"
                >
                    <slot />
                </div>
            </div>
        </SidebarInset>
    </SidebarProvider>

    <Notifications />
</template>
