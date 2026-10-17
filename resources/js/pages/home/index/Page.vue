<script setup lang="ts">
import { Container } from '@/components/container';
import Seo from '@/components/Seo.vue';
</script>

<template>
    <Seo title="Inicio" />

    <Container>
        <h1 class="text-3xl font-semibold tracking-tight lg:text-4xl">
            {{ $page.props.name }}
        </h1>
        <p class="max-w-2xl text-muted-foreground">
            Un punto de partida con Laravel, Inertia y Vue: panel de
            administración en subdominio, roles y permisos, y componentes listos
            para construir tu aplicación.
        </p>
    </Container>
</template>
