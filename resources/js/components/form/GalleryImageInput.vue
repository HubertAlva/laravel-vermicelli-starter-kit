<script lang="ts" setup>
import {
    Field,
    FieldDescription,
    FieldError,
    FieldLabel,
} from '@/components/ui/field';
import { cn } from '@/lib/utils';
import { RotateCcw, Upload, X } from 'lucide-vue-next';
import { computed, onBeforeUnmount, ref } from 'vue';

interface Props {
    label?: string;
    description?: string;
    error?: string | undefined;
}

const props = defineProps<Props>();

defineOptions({
    inheritAttrs: false,
});

const emit = defineEmits<{
    (e: 'validate'): void;
}>();

interface GalleryItem {
    file?: File;
    url?: string;
    isDeleted: boolean;
}

const model = defineModel<(File | string | GalleryItem)[]>({
    default: () => [],
});

const normalizedModel = computed({
    get: () => {
        return model.value.map((item) => {
            if (typeof item === 'string') {
                return { url: item, isDeleted: false };
            }
            if (item instanceof File) {
                return { file: item, isDeleted: false };
            }
            return item as GalleryItem;
        });
    },
    set: (newValue) => {
        model.value = newValue;
    },
});

const isDragging = ref(false);
const fileInputRef = ref<HTMLInputElement | null>(null);

const ACCEPTED_MIME_TYPES = ['image/png', 'image/jpeg', 'image/webp'];
const MAX_FILE_SIZE_BYTES = 10 * 1024 * 1024; // 10MB, matches the server-side thumbnail limit

// Object URLs are created once per File and cached here, so re-renders never
// leak blob URLs. Entries are revoked when their file is removed or the
// component unmounts.
const objectUrlCache = new WeakMap<File, string>();
const createdUrls = new Set<string>();

const triggerFileInput = () => {
    if (fileInputRef.value) {
        fileInputRef.value.click();
    }
};

const getPreviewUrl = (item: GalleryItem) => {
    if (item.url) {
        return item.url;
    }
    if (item.file) {
        let url = objectUrlCache.get(item.file);
        if (!url) {
            url = URL.createObjectURL(item.file);
            objectUrlCache.set(item.file, url);
            createdUrls.add(url);
        }
        return url;
    }
    return '';
};

const revokePreviewUrl = (file: File) => {
    const url = objectUrlCache.get(file);
    if (url) {
        URL.revokeObjectURL(url);
        objectUrlCache.delete(file);
        createdUrls.delete(url);
    }
};

// Stable per-file identity for :key, independent of array position, so
// removing one item doesn't cause the rest to shift keys.
let nextFileKey = 0;
const fileKeys = new WeakMap<File, string>();

const getItemKey = (item: GalleryItem, index: number): string | number => {
    if (item.url) {
        return item.url;
    }
    if (item.file) {
        let key = fileKeys.get(item.file);
        if (!key) {
            key = `file-${nextFileKey++}`;
            fileKeys.set(item.file, key);
        }
        return key;
    }
    return index;
};

onBeforeUnmount(() => {
    createdUrls.forEach((url) => URL.revokeObjectURL(url));
    createdUrls.clear();
});

const onFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const files = target.files;

    if (files) {
        addFiles(Array.from(files));
    }

    if (fileInputRef.value) {
        fileInputRef.value.value = '';
    }

    emit('validate');
};

const addFiles = (files: File[]) => {
    const validFiles = files.filter(
        (file) =>
            ACCEPTED_MIME_TYPES.includes(file.type) &&
            file.size <= MAX_FILE_SIZE_BYTES,
    );

    const newItems = validFiles.map((file) => ({ file, isDeleted: false }));
    model.value = [...model.value, ...newItems];
};

const toggleDelete = (index: number) => {
    const item = normalizedModel.value[index];
    if (item.file) {
        // If it's a new file, revoke its preview URL and remove it from the list
        revokePreviewUrl(item.file);
        const newModel = [...model.value];
        newModel.splice(index, 1);
        model.value = newModel;
    } else {
        // If it's an existing image, toggle the isDeleted flag
        const newModel = [...model.value];
        const currentItem = newModel[index];

        if (typeof currentItem === 'string') {
            newModel[index] = { url: currentItem, isDeleted: true };
        } else if (typeof currentItem === 'object' && currentItem !== null) {
            (newModel[index] as GalleryItem).isDeleted = !(
                currentItem as GalleryItem
            ).isDeleted;
        }
        model.value = newModel;
    }
    emit('validate');
};

const onDrop = (event: DragEvent) => {
    event.preventDefault();
    isDragging.value = false;

    const files = event.dataTransfer?.files;
    if (files && files.length > 0) {
        addFiles(Array.from(files));
        emit('validate');
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
</script>

<template>
    <Field :data-invalid="!!props.error">
        <FieldLabel v-if="props.label">
            {{ props.label }}
        </FieldLabel>

        <div class="flex flex-col gap-4">
            <!-- Dropzone -->
            <button
                :class="
                    cn(
                        'group flex min-h-36 w-full cursor-pointer flex-col items-center justify-center gap-2 rounded-lg border-2 border-dashed p-4 text-center transition',
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
                <div class="flex flex-col items-center justify-center gap-3">
                    <Upload class="size-6 text-muted-foreground" />
                    <p class="text-sm text-muted-foreground">
                        Arrastra y suelta o selecciona archivos para la galería
                    </p>
                </div>
            </button>

            <!-- Preview List -->
            <div
                v-if="normalizedModel.length > 0"
                class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4"
            >
                <div
                    v-for="(item, index) in normalizedModel"
                    :key="getItemKey(item, index)"
                    :class="
                        cn(
                            'group relative aspect-square overflow-hidden rounded-lg border border-gray-200 bg-gray-100 transition-all dark:border-gray-800 dark:bg-neutral-900',
                            item.isDeleted ? 'opacity-50 grayscale' : '',
                        )
                    "
                >
                    <img
                        :src="getPreviewUrl(item)"
                        alt="Preview"
                        class="h-full w-full object-cover"
                    />
                    <div
                        v-if="item.isDeleted"
                        class="absolute inset-0 flex items-center justify-center bg-black/20"
                    >
                        <span
                            class="rounded bg-black/50 px-2 py-1 text-xs font-medium text-white"
                        >
                            Eliminado
                        </span>
                    </div>
                    <button
                        :class="
                            cn(
                                'absolute top-1 right-1 flex size-6 cursor-pointer items-center justify-center rounded-full text-white transition-opacity group-hover:opacity-100',
                                item.isDeleted
                                    ? 'bg-blue-500 opacity-100 hover:bg-blue-600'
                                    : 'bg-red-500 opacity-0 hover:bg-red-600',
                            )
                        "
                        type="button"
                        @click="toggleDelete(index)"
                    >
                        <RotateCcw v-if="item.isDeleted" class="size-4" />
                        <X v-else class="size-4" />
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
            multiple
            type="file"
            v-bind="$attrs"
            @change="onFileChange"
        />
    </Field>
</template>
