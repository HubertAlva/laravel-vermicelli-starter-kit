<script lang="ts" setup>
import { Button } from '@/components/ui/button';
import { useAppearance } from '@/composables/useAppearance';
import { Moon, Sun } from 'lucide-vue-next';

const { appearance, updateAppearance } = useAppearance();

const toggleAppearance = () => {
    const newAppearance = appearance.value === 'light' ? 'dark' : 'light';
    updateAppearance(newAppearance);
};
</script>

<template>
    <Button size="icon" variant="outline" @click="toggleAppearance">
        <component :is="appearance === 'light' ? Moon : Sun" class="size-4" />
    </Button>
</template>
