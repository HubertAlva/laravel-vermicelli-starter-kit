<script lang="ts" setup>
import { Toaster } from '@/components/ui/sonner';
import { type ToasterProps } from 'vue-sonner';
import 'vue-sonner/style.css';

const {
    position = 'top-right',
    duration = 5000,
    closeButton = true,
    richColors = true,
} = defineProps<ToasterProps>();
</script>
<template>
    <Toaster
        :closeButton="closeButton"
        :duration="duration"
        :position="position"
        :richColors="richColors"
    />
</template>
