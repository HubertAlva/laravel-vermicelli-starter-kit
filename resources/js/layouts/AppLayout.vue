<script lang="ts" setup>
import Footer from '@/layouts/app/Footer.vue';
import Header from '@/layouts/app/Header.vue';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';

interface Props {
    head: {
        title: string;
        description?: string;
        image?: string;
    };
}

const defaultDescription = 'Starter Kit hecho por Hubert Alva.';
const defaultImage = '/images/og-image.png';

const metaDescription = computed(() => head.description ?? defaultDescription);

const metaImage = computed(() => head.image ?? defaultImage);

const { head } = defineProps<Props>();
</script>

<template>
    <Head>
        <title>{{ head.title }}</title>

        <link :href="metaImage" rel="apple-touch-icon" />
        <meta :content="metaDescription" name="description" />

        <meta content="website" property="og:type" />
        <meta :content="head.title" property="og:site_name" />

        <meta :content="head.title" property="og:title" />
        <meta :content="metaDescription" property="og:description" />

        <meta :content="metaImage" property="og:image" />
    </Head>

    <Header />

    <main class="pt-[60px] xl:pt-[68px]">
        <slot />
    </main>

    <Footer />
</template>
