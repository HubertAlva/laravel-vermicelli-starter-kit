<script setup lang="ts">
import { RotateCcw, X } from '@lucide/vue';
import { onBeforeUnmount } from 'vue';
import { useFileDrop } from '@/composables/useFileDrop';
import { cn } from '@/lib/utils';
import Dropzone from './Dropzone.vue';
import FormField from './FormField.vue';
import type { BaseInputProps, GalleryItem } from './types';

defineOptions({ inheritAttrs: false });

const {
    id,
    label,
    description,
    error,
    accept = ['image/png', 'image/jpeg', 'image/webp'],
    maxSize = 10 * 1024 * 1024,
} = defineProps<BaseInputProps & { accept?: string[]; maxSize?: number }>();

const model = defineModel<GalleryItem[]>({ default: () => [] });

const emit = defineEmits<{ validate: [] }>();

const {
    inputRef,
    isDragging,
    openFilePicker,
    onInputChange,
    dropzoneListeners,
} = useFileDrop({
    accept,
    maxSize,
    onFiles: (files) => {
        model.value = [...model.value, ...files.map((file) => ({ file }))];
        emit('validate');
    },
});

// One object URL per file, revoked when the file is removed or on unmount...
const objectUrls = new Map<File, string>();

function previewUrl(item: GalleryItem): string {
    if (!item.file) {
        return item.url;
    }

    if (!objectUrls.has(item.file)) {
        objectUrls.set(item.file, URL.createObjectURL(item.file));
    }

    return objectUrls.get(item.file)!;
}

function itemKey(item: GalleryItem): string {
    return item.file ? previewUrl(item) : item.url;
}

function toggle(index: number): void {
    const item = model.value[index];

    if (item.file) {
        URL.revokeObjectURL(objectUrls.get(item.file) ?? '');
        objectUrls.delete(item.file);
        model.value = model.value.filter((_, i) => i !== index);
    } else {
        model.value = model.value.map((current, i) =>
            i === index ? { url: item.url, deleted: !item.deleted } : current,
        );
    }

    emit('validate');
}

onBeforeUnmount(() => objectUrls.forEach((url) => URL.revokeObjectURL(url)));
</script>

<template>
    <FormField :label :for="id" :description :error>
        <input
            :id
            ref="inputRef"
            type="file"
            class="hidden"
            multiple
            :accept="accept.join(',')"
            v-bind="$attrs"
            @change="onInputChange"
        />

        <Dropzone
            :is-dragging
            :invalid="!!error"
            text="Arrastra y suelta o selecciona imágenes para la galería"
            @click="openFilePicker"
            v-on="dropzoneListeners"
        />

        <div
            v-if="model.length"
            class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4"
        >
            <div
                v-for="(item, index) in model"
                :key="itemKey(item)"
                :class="
                    cn(
                        'group relative aspect-square overflow-hidden rounded-lg border bg-muted',
                        item.deleted && 'opacity-50 grayscale',
                    )
                "
            >
                <img
                    :src="previewUrl(item)"
                    alt=""
                    class="size-full object-cover"
                />

                <span
                    v-if="item.deleted"
                    class="absolute inset-0 m-auto h-fit w-fit rounded bg-black/60 px-2 py-1 text-xs font-medium text-white"
                >
                    Eliminado
                </span>

                <button
                    type="button"
                    :aria-label="
                        item.deleted ? 'Restaurar imagen' : 'Quitar imagen'
                    "
                    :class="
                        cn(
                            'absolute top-1 right-1 flex size-6 cursor-pointer items-center justify-center rounded-full text-white transition-opacity group-hover:opacity-100 focus-visible:opacity-100',
                            item.deleted
                                ? 'bg-primary opacity-100'
                                : 'bg-destructive opacity-0',
                        )
                    "
                    @click="toggle(index)"
                >
                    <RotateCcw v-if="item.deleted" class="size-4" />
                    <X v-else class="size-4" />
                </button>
            </div>
        </div>
    </FormField>
</template>
