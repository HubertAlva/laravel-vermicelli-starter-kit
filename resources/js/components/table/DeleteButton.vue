<script lang="ts" setup>
import { Button } from '@/components/ui/button';
import { Trash } from 'lucide-vue-next';

const props = defineProps<{
    modelId: number;
}>();

const emit = defineEmits<{
    (e: 'delete', modelId: number): void;
}>();

const handleClick = () => {
    emit('delete', props.modelId);
};
</script>

<template>
    <Button
        aria-label="Eliminar"
        size="icon"
        type="button"
        variant="destructive"
        @click.stop="handleClick"
    >
        <Trash />
    </Button>
</template>
