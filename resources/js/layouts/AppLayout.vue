<script lang="ts" setup>
import Footer from '@/layouts/app/Footer.vue';
import Header from '@/layouts/app/Header.vue';
import { Head } from '@inertiajs/vue3';

interface Props {
    title: string;
    description?: string;
    image?: string;
}

const defaultDescription = 'Starter Kit hecho por Hubert Alva.';

const {
    title,
    description = defaultDescription,
    image = '/images/og-image.png',
} = defineProps<Props>();
</script>

<template>
    <Head>
        <title>{{ title }}</title>

        <link :href="image" rel="apple-touch-icon" />
        <meta :content="description" name="description" />

        <meta content="website" property="og:type" />
        <meta :content="title" property="og:site_name" />

        <meta :content="title" property="og:title" />
        <meta :content="description" property="og:description" />

        <meta :content="image" property="og:image" />
    </Head>

    <Header />

    <main class="pt-[60px] xl:pt-[68px]">
        <slot />
    </main>

    <Footer />
</template>
