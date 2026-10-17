<script setup lang="ts">
import { Moon, Sun } from '@lucide/vue';
import { Button } from '@/components/ui/button';
import { useAppearance } from '@/composables/useAppearance';

const { resolvedAppearance, updateAppearance } = useAppearance();

function toggleAppearance(): void {
    updateAppearance(resolvedAppearance.value === 'dark' ? 'light' : 'dark');
}
</script>

<template>
    <Button
        size="icon"
        variant="outline"
        aria-label="Cambiar tema"
        @click="toggleAppearance"
    >
        <!-- Toggled with CSS: the server can't know the theme, so rendering
             one icon conditionally would cause a hydration mismatch -->
        <Sun class="hidden size-4 dark:block" />
        <Moon class="size-4 dark:hidden" />
    </Button>
</template>
