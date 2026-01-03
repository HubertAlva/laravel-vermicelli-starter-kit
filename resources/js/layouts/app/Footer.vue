<script lang="ts" setup>
import { Container } from '@/components/container';

const year = new Date().getFullYear();
</script>

<template>
    <footer class="bg-neutral-950 text-white">
        <Container as="div">
            <small>
                © {{ year }} Laravel. Todos los derechos reservados.
            </small>
        </Container>
    </footer>
</template>
