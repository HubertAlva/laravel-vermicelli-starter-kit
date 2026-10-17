<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';

const { processing = false } = defineProps<{ processing?: boolean }>();
</script>

<template>
    <Button type="submit" :disabled="processing">
        <slot />
        <Spinner v-if="processing" />
    </Button>
</template>
