<script setup lang="ts">
import 'vue-sonner/style.css'
import { Toaster } from "@/components/ui/sonner";
import { toast } from "vue-sonner";
import { usePage } from "@inertiajs/vue3";
import { type ToasterProps } from "vue-sonner";
import { computed, onMounted, watch } from "vue";

const {
    position = "top-right",
    duration = 5000,
    closeButton = true,
    richColors = true,
} = defineProps<ToasterProps>();

const page = usePage();
const flash = computed(() => page.props.flash) as any;

// Show the flash message when the component is mounted
onMounted(() => {
    if (flash.value.success) {
        toast.success("Éxito", {
            description: flash.value.success,
        });
    }

    if (flash.value.info) {
        toast.info("Info", {
            description: flash.value.info,
        });
    }

    if (flash.value.error) {
        toast.error("Error", {
            description: flash.value.error,
        });
    }

    if (flash.value.warn) {
        toast.warning("Advertencia", {
            description: flash.value.warn,
        });
    }
});

// Watch for changes in the flash prop
watch(flash, (value) => {
    if (value.success) {
        toast.success("Éxito", {
            description: value.success,
        });
    }

    if (value.info) {
        toast.info("Info", {
            description: value.info,
        });
    }

    if (value.error) {
        toast.error("Error", {
            description: value.error,
        });
    }

    if (value.warn) {
        toast.warning("Advertencia", {
            description: value.warn,
        });
    }
});
</script>
<template>
    <Toaster
        v-bind="{
            duration,
            position,
            closeButton,
            richColors,
        }"
    />
</template>
