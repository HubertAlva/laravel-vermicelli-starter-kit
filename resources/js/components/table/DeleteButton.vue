<script setup lang="ts">
import { Trash } from '@lucide/vue';
import { Button } from '@/components/ui/button';

const { label = 'Eliminar' } = defineProps<{ label?: string }>();

const emit = defineEmits<{ delete: [] }>();
</script>

<template>
    <Button
        type="button"
        size="icon"
        variant="destructive"
        :aria-label="label"
        :title="label"
        @click.stop="emit('delete')"
    >
        <Trash />
    </Button>
</template>
