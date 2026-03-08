<script lang="ts" setup>
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
import { Button } from '@/components/ui/button';
import AdminLayout from '@/layouts/AdminLayout.vue';
import testForm from '@/routes/admin/test-form';
import { Link } from '@inertiajs/vue3';

const title = 'Dashboard';

const breadcrumb = [
    {
        name: title,
    },
];
</script>

<template>
    <AdminLayout :breadcrumb="breadcrumb" :title="title">
        <div class="mb-4 grid auto-rows-min gap-4 md:grid-cols-3">
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
            >
                <PlaceholderPattern />
            </div>
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
            >
                <PlaceholderPattern />
            </div>
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
            >
                <PlaceholderPattern />
            </div>
        </div>

        <div>
            <Button asChild>
                <Link :href="testForm.index().url">Formulario de prueba</Link>
            </Button>
        </div>
    </AdminLayout>
</template>
