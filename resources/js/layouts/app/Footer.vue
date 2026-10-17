<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { Container } from '@/components/container';

const page = usePage();
const year = new Date().getFullYear();
</script>

<template>
    <footer class="bg-neutral-950 text-white dark:bg-neutral-900">
        <Container as="div" class="py-8 lg:py-8">
            <small>
                © {{ year }} {{ page.props.name }}. Todos los derechos
                reservados.
            </small>
        </Container>
    </footer>
</template>
