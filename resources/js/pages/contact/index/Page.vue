<script setup lang="ts">
import ContactForm from '@/components/ContactForm.vue';
import { Container } from '@/components/container';
import Seo from '@/components/Seo.vue';
</script>

<template>
    <Seo title="Contacto" description="Escríbenos y te responderemos pronto." />

    <Container class="max-w-2xl">
        <header class="space-y-2">
            <h1 class="text-3xl font-bold tracking-tight">Contacto</h1>
            <p class="text-muted-foreground">
                Escríbenos y te responderemos lo antes posible.
            </p>
        </header>

        <ContactForm />
    </Container>
</template>
