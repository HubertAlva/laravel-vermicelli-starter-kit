<script setup lang="ts">
import { File as FileIcon, FileX, X } from '@lucide/vue';
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import { useFileDrop } from '@/composables/useFileDrop';
import { cn, formatSize } from '@/lib/utils';
import Dropzone from './Dropzone.vue';
import FormField from './FormField.vue';
import type { BaseInputProps } from './types';

defineOptions({ inheritAttrs: false });

const {
    id,
    label,
    description,
    error,
    accept = [],
    maxSize,
} = defineProps<
    BaseInputProps & {
        /** Accepted MIME types; anything is accepted when empty. */
        accept?: string[];
        /** Maximum file size in bytes. */
        maxSize?: number;
    }
>();

const model = defineModel<File | string | null>();

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
        emit('validate');
    },
});

const fileSize = computed(() =>
    model.value instanceof File ? formatSize(model.value.size) : null,
);

const fileName = computed(() =>
    model.value instanceof File
        ? model.value.name
        : model.value?.split('/').pop(),
);

function remove(): void {
    model.value = null;
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
            :accept="accept.join(',') || undefined"
            v-bind="$attrs"
            @change="onInputChange"
        />

        <Dropzone
            v-if="!model"
            :is-dragging
            :invalid="!!error"
            @click="openFilePicker"
            v-on="dropzoneListeners"
        />

        <div
            v-else
            class="flex animate-in items-center gap-4 rounded-xl border bg-card p-4 duration-300 fade-in slide-in-from-bottom-2"
        >
            <div
                :class="
                    cn(
                        'rounded-lg p-3',
                        error ? 'bg-destructive/10' : 'bg-primary/10',
                    )
                "
            >
                <component
                    :is="error ? FileX : FileIcon"
                    :class="
                        cn(
                            'size-6',
                            error ? 'text-destructive' : 'text-primary',
                        )
                    "
                />
            </div>
            <div class="min-w-0 flex-1">
                <p
                    :class="
                        cn(
                            'truncate text-sm font-semibold',
                            error && 'text-destructive',
                        )
                    "
                >
                    {{ fileName }}
                </p>
                <p v-if="fileSize" class="mt-0.5 text-xs text-muted-foreground">
                    {{ fileSize }}
                </p>
            </div>
            <Button
                type="button"
                size="icon"
                variant="ghost"
                class="shrink-0 rounded-full hover:bg-destructive/10 hover:text-destructive"
                aria-label="Quitar archivo"
                @click="remove"
            >
                <X class="size-4" />
            </Button>
        </div>
    </FormField>
</template>
