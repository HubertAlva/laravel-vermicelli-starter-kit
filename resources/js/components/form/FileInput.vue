<script lang="ts" setup>
import { Button } from '@/components/ui/button';
import {
    Field,
    FieldDescription,
    FieldError,
    FieldLabel,
} from '@/components/ui/field';
import { cn, formatSize } from '@/lib/utils';
import { File, FileX, Upload, X } from 'lucide-vue-next';
import { ref } from 'vue';

interface Props {
    label?: string;
    id: string;
    description?: string;
    error?: string | undefined;
}

const emit = defineEmits<{
    (e: 'validate'): void;
}>();

const props = defineProps<Props>();

defineOptions({
    inheritAttrs: false,
});

const model = defineModel<string | File | null>();

const isDragging = ref(false);

const fileInputRef = ref<HTMLInputElement | null>(null);

const triggerFileInput = () => {
    if (fileInputRef.value) {
        fileInputRef.value.click();
    }
};

const onFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;

    model.value = target.files?.[0] || null;

    emit('validate');
};

const onDrop = (event: DragEvent) => {
    event.preventDefault();
    isDragging.value = false;

    const files = event.dataTransfer?.files;

    if (files && files.length > 0) {
        const file = files[0];

        model.value = file;

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

const removeFile = () => {
    model.value = null;

    if (fileInputRef.value) {
        fileInputRef.value.value = '';
    }

    emit('validate');
};
</script>

<template>
    <Field :data-invalid="!!props.error">
        <FieldLabel v-if="props.label" :for="props.id">
            {{ props.label }}
        </FieldLabel>

        <input
            :id="props.id"
            ref="fileInputRef"
            class="hidden"
            type="file"
            v-bind="$attrs"
            @change="onFileChange"
        />

        <div
            v-if="!model"
            :class="
                cn(
                    'group relative flex min-h-36 cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed p-10 transition-all',
                    isDragging
                        ? 'border-blue-400 bg-blue-50/50 dark:border-blue-700 dark:bg-blue-600/20'
                        : 'border-gray-300 bg-gray-50 hover:border-blue-400 hover:bg-blue-50/50 dark:border-gray-600 dark:bg-neutral-900 dark:hover:border-blue-700 dark:hover:bg-blue-600/20',
                )
            "
            @click="triggerFileInput"
            @dragleave="onDragLeave"
            @dragover="onDragOver"
            @drop="onDrop"
        >
            <div class="flex flex-col items-center gap-3 text-center">
                <Upload
                    class="h-6 w-6 text-muted-foreground transition-colors"
                />
                <p class="text-sm text-muted-foreground">
                    Arrastra y suelta o selecciona un archivo
                </p>
            </div>
        </div>

        <div
            v-else
            class="flex animate-in items-center gap-4 rounded-xl border border-border bg-card p-4 duration-300 fade-in slide-in-from-bottom-2"
        >
            <div
                :class="
                    cn(
                        'rounded-lg p-3',
                        props.error ? 'bg-destructive/10' : 'bg-primary/10',
                    )
                "
            >
                <component
                    :is="props.error ? FileX : File"
                    :class="
                        cn(
                            'h-6 w-6',
                            props.error ? 'text-destructive' : 'text-primary',
                        )
                    "
                />
            </div>
            <div class="min-w-0 flex-1">
                <p
                    :class="
                        cn(
                            'truncate text-sm font-semibold text-foreground',
                            props.error
                                ? 'text-destructive'
                                : 'text-foreground',
                        )
                    "
                >
                    {{ model.name }}
                </p>
                <p class="mt-0.5 text-xs text-muted-foreground">
                    {{ formatSize(model.size) }}
                </p>
            </div>
            <Button
                class="shrink-0 rounded-full hover:bg-destructive/10 hover:text-destructive"
                size="icon"
                variant="ghost"
                @click="removeFile"
            >
                <X class="h-4 w-4" />
                <span class="sr-only"> Quitar archivo </span>
            </Button>
        </div>

        <FieldDescription v-if="props.description">
            {{ props.description }}
        </FieldDescription>

        <FieldError v-if="props.error" :errors="[props.error]" />
    </Field>
</template>
