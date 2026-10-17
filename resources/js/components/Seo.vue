<script setup lang="ts">
/**
 * Page title, description and Open Graph tags. Crawlers only see these with
 * SSR enabled, and `image` should be an absolute URL in production.
 */
import { Head } from '@inertiajs/vue3';

const {
    title,
    description = 'Starter kit de Laravel, Inertia y Vue.',
    image = '/images/og-image.png',
    type = 'website',
} = defineProps<{
    title: string;
    description?: string;
    image?: string;
    type?: string;
}>();

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
</script>

<template>
    <Head :title="title">
        <meta
            head-key="description"
            name="description"
            :content="description"
        />

        <meta head-key="og:type" property="og:type" :content="type" />
        <meta
            head-key="og:site_name"
            property="og:site_name"
            :content="appName"
        />
        <meta head-key="og:title" property="og:title" :content="title" />
        <meta
            head-key="og:description"
            property="og:description"
            :content="description"
        />
        <meta head-key="og:image" property="og:image" :content="image" />

        <meta
            head-key="twitter:card"
            name="twitter:card"
            content="summary_large_image"
        />
    </Head>
</template>
