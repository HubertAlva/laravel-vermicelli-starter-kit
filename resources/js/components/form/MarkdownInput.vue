<script setup lang="ts">
import { http } from '@inertiajs/vue3';
import ES_ES from '@vavt/cm-extension/dist/locale/es-ES';
import { config, MdEditor } from 'md-editor-v3';
import type { ToolbarNames } from 'md-editor-v3';
import 'md-editor-v3/lib/style.css';
import { toast } from 'vue-sonner';
import { useAppearance } from '@/composables/useAppearance';
import { cn } from '@/lib/utils';
import { store as storeEditorImage } from '@/routes/admin/editor/images';
import FormField from './FormField.vue';
import type { BaseInputProps } from './types';

config({
    editorConfig: {
        languageUserDefined: { 'es-ES': ES_ES },
    },
});

const DEFAULT_EXCLUDED_TOOLBARS: ToolbarNames[] = [
    'table',
    'mermaid',
    'katex',
    'revoke',
    'next',
    'save',
    'prettier',
    'fullscreen',
    'previewOnly',
    'htmlPreview',
    'catalog',
    'github',
];

/**
 * Markdown editor. Pasted or uploaded images are stored temporarily and
 * attached to the model when the form is saved.
 */
const {
    id,
    label,
    description,
    error,
    excludeToolbars = [],
    class: className,
} = defineProps<
    BaseInputProps & { excludeToolbars?: ToolbarNames[]; class?: string }
>();

const model = defineModel<string | null>();

const emit = defineEmits<{ validate: [] }>();

const { resolvedAppearance } = useAppearance();

async function uploadImage(file: File): Promise<string> {
    const data = new FormData();
    data.append('image', file);

    const response = await http.getClient().request({
        method: 'post',
        url: storeEditorImage().url,
        data,
        headers: { Accept: 'application/json' },
    });

    return (JSON.parse(response.data) as { url: string }).url;
}

async function onUploadImg(files: File[], callback: (urls: string[]) => void) {
    try {
        callback(await Promise.all(files.map(uploadImage)));
    } catch {
        toast.error('No se pudo subir la imagen.');
    }
}
</script>

<template>
    <FormField
        :label
        :for="id"
        :description
        :error
        :class="cn('min-w-0', className)"
    >
        <MdEditor
            :id
            :model-value="model ?? ''"
            :preview="false"
            :toolbars-exclude="[
                ...DEFAULT_EXCLUDED_TOOLBARS,
                ...excludeToolbars,
            ]"
            :theme="resolvedAppearance"
            language="es-ES"
            no-img-zoom-in
            @update:model-value="
                (value: string) => {
                    model = value;
                    emit('validate');
                }
            "
            @on-upload-img="onUploadImg"
        />
    </FormField>
</template>
