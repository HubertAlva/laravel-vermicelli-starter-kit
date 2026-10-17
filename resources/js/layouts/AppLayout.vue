<script setup lang="ts">
import AppToaster from '@/components/AppToaster.vue';
import Footer from '@/layouts/app/Footer.vue';
import Header from '@/layouts/app/Header.vue';
</script>

<template>
    <div class="flex min-h-svh flex-col">
        <Header />

        <main class="flex-1 pt-15 xl:pt-17">
            <slot />
        </main>

        <Footer />

        <AppToaster />
    </div>
</template>
