<script lang="ts" setup>
import { Button } from '@/components/ui/button';
import AdminLayout from '@/layouts/AdminLayout.vue';
import posts from '@/routes/admin/posts';
import { Link } from '@inertiajs/vue3';

const title = 'Artículos';

const breadcrumb = [
    {
        name: title,
    },
];
</script>

<template>
    <AdminLayout :breadcrumb="breadcrumb" :title="title">
        <div class="flex items-center justify-between gap-4">
            <h1>{{ title }}</h1>

            <Button>
                <Link :href="posts.create().url"> Nuevo artículo </Link>
            </Button>
        </div>
    </AdminLayout>
</template>
