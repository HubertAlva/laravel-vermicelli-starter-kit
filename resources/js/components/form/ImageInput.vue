<script lang="ts" setup>
import {
    Field,
    FieldDescription,
    FieldError,
    FieldLabel,
} from '@/components/ui/field';
import { cn } from '@/lib/utils';
import { Upload } from 'lucide-vue-next';
import { computed, ref } from 'vue';

interface Props {
    label?: string;
    description?: string;
    currentImageUrl?: string | null;
    isNewImage: boolean;
    error?: string | undefined;
}

const props = defineProps<Props>();

defineOptions({
    inheritAttrs: false,
});

const emit = defineEmits<{
    (e: 'update:isNewImage', value: boolean): void;
    (e: 'validate'): void;
}>();

const isNewImage = computed({
    get: () => props.isNewImage,
    set: (value) => emit('update:isNewImage', value),
});

const model = defineModel<string | File | null>();

const previewUrl = ref<string | null>(props.currentImageUrl ?? null);

const isDragging = ref(false);

const fileInputRef = ref<HTMLInputElement | null>(null);

const triggerFileInput = () => {
    if (fileInputRef.value) {
        fileInputRef.value.click();
    }
};

const onFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0] || null;

    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            previewUrl.value = e.target?.result as string;
        };
        reader.readAsDataURL(file);
    } else {
        clearImage();
    }

    model.value = file;

    emit('validate');

    isNewImage.value = true;
};

const onDrop = (event: DragEvent) => {
    event.preventDefault();
    isDragging.value = false;

    const files = event.dataTransfer?.files;
    if (files && files.length > 0) {
        const file = files[0];

        const reader = new FileReader();
        reader.onload = (e) => {
            previewUrl.value = e.target?.result as string;
        };
        reader.readAsDataURL(file);
        model.value = file;

        emit('validate');

        isNewImage.value = true;
    }
};

const onDragOver = (event: DragEvent) => {
    event.preventDefault();
    isDragging.value = true;
};

const onDragLeave = (event: DragEvent) => {
    event.preventDefault();
    isDragging.value = false;
};

const clearImage = () => {
    previewUrl.value = null;
    model.value = null;

    if (fileInputRef.value) {
        fileInputRef.value.value = '';
    }

    emit('validate');

    isNewImage.value = false;
};
</script>

<template>
    <Field :data-invalid="!!props.error">
        <FieldLabel v-if="props.label">
            {{ props.label }}
        </FieldLabel>

        <div class="flex flex-col items-center justify-start gap-2">
            <div class="w-full">
                <button
                    :class="
                        cn(
                            'group flex min-h-36 w-full cursor-pointer flex-col items-center justify-center gap-2 rounded-lg border-2 border-dashed p-1 text-center transition',
                            isDragging
                                ? 'border-blue-400 bg-blue-50/50 dark:border-blue-700 dark:bg-blue-600/20'
                                : 'border-gray-300 bg-gray-50 hover:border-blue-400 hover:bg-blue-50/50 dark:border-gray-600 dark:bg-neutral-900 dark:hover:border-blue-700 dark:hover:bg-blue-600/20',
                            props.error
                                ? 'border-red-500 bg-red-50 dark:border-red-700 dark:bg-red-600/20'
                                : '',
                        )
                    "
                    type="button"
                    @click="triggerFileInput"
                    @dragleave="onDragLeave"
                    @dragover="onDragOver"
                    @drop="onDrop"
                >
                    <img
                        v-if="previewUrl"
                        :src="previewUrl"
                        alt="Preview"
                        class="h-36 rounded object-contain"
                    />

                    <template v-else>
                        <div
                            class="flex flex-col items-center justify-center gap-3"
                        >
                            <Upload class="size-6 text-muted-foreground" />
                            <p class="text-sm text-muted-foreground">
                                Arrastra y suelta o selecciona un archivo
                            </p>
                        </div>
                    </template>
                </button>

                <div
                    v-if="previewUrl"
                    class="flex w-full items-center justify-end px-2 py-1"
                >
                    <button
                        class="flex cursor-pointer items-center justify-center rounded-lg border border-gray-600 bg-gray-50 px-2 py-1 text-center text-xs leading-none text-gray-600 uppercase hover:border-red-600 hover:bg-red-50 hover:text-red-500 dark:bg-neutral-900 dark:text-gray-400 dark:hover:border-red-500 dark:hover:bg-red-600/20 dark:hover:text-red-500"
                        type="button"
                        @click="clearImage"
                    >
                        Quitar imagen
                    </button>
                </div>
            </div>
        </div>

        <FieldDescription v-if="props.description">
            {{ props.description }}
        </FieldDescription>

        <FieldError v-if="props.error" :errors="[props.error]" />

        <input
            ref="fileInputRef"
            accept="image/png, image/jpeg, image/webp"
            class="hidden"
            type="file"
            v-bind="$attrs"
            @change="onFileChange"
        />
    </Field>
</template>
