<script setup lang="ts">
import { onBeforeUnmount, ref, watch } from 'vue';
import { Button } from '@/components/ui/button';
import { useFileDrop } from '@/composables/useFileDrop';
import Dropzone from './Dropzone.vue';
import FormField from './FormField.vue';
import type { BaseInputProps } from './types';

defineOptions({ inheritAttrs: false });

/**
 * Single image upload with preview. The model is the new `File`, or the URL
 * of the current image. `isNewImage` tells the server whether to replace it.
 */
const {
    id,
    label,
    description,
    error,
    accept = ['image/png', 'image/jpeg', 'image/webp'],
    maxSize = 10 * 1024 * 1024,
} = defineProps<BaseInputProps & { accept?: string[]; maxSize?: number }>();

const model = defineModel<File | string | null>();
const isNewImage = defineModel<boolean>('isNewImage', { default: false });

const emit = defineEmits<{ validate: [] }>();

const {
    inputRef,
    isDragging,
    openFilePicker,
    clearInput,
    onInputChange,
    dropzoneListeners,
} = useFileDrop({
    accept,
    maxSize,
    onFiles: ([file]) => {
        model.value = file;
        isNewImage.value = true;
        emit('validate');
    },
});

const previewUrl = ref<string | null>(null);
let objectUrl: string | null = null;

function revokeObjectUrl(): void {
    if (objectUrl) {
        URL.revokeObjectURL(objectUrl);
        objectUrl = null;
    }
}

watch(
    model,
    (value) => {
        revokeObjectUrl();

        if (value instanceof File) {
            objectUrl = URL.createObjectURL(value);
        }

        previewUrl.value =
            objectUrl ?? (typeof value === 'string' && value ? value : null);
    },
    { immediate: true },
);

onBeforeUnmount(revokeObjectUrl);

function remove(): void {
    model.value = null;
    isNewImage.value = false;
    clearInput();
    emit('validate');
}
</script>

<template>
    <FormField :label :for="id" :description :error>
        <input
            :id
            ref="inputRef"
            type="file"
            class="hidden"
            :accept="accept.join(',')"
            v-bind="$attrs"
            @change="onInputChange"
        />

        <Dropzone
            :is-dragging
            :invalid="!!error"
            class="p-1"
            @click="openFilePicker"
            v-on="dropzoneListeners"
        >
            <img
                v-if="previewUrl"
                :src="previewUrl"
                alt="Vista previa"
                class="h-36 rounded object-contain"
            />
        </Dropzone>

        <div v-if="previewUrl" class="flex justify-end">
            <Button type="button" size="sm" variant="outline" @click="remove">
                Quitar imagen
            </Button>
        </div>
    </FormField>
</template>
