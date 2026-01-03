<script lang="ts" setup>
import {
    Field,
    FieldDescription,
    FieldError,
    FieldLabel,
} from '@/components/ui/field';
import { cn } from '@/lib/utils';
import editor from '@/routes/admin/editor';
import ES_ES from '@vavt/cm-extension/dist/locale/es-ES';
import axios from 'axios';
import { config, MdEditor, type ToolbarNames } from 'md-editor-v3';
import 'md-editor-v3/lib/style.css';
import type { HTMLAttributes } from 'vue';

interface Props {
    label?: string;
    description?: string;
    error?: string | null;
    extraToolbarsExclude?: ToolbarNames[];
    class?: HTMLAttributes['class'];
}

const props = defineProps<Props>();

const emit = defineEmits<{
    (e: 'validate'): void;
}>();

const model = defineModel<string | undefined>();

config({
    editorConfig: {
        languageUserDefined: {
            'es-ES': ES_ES,
        },
    },
});

const toolbarsExclude = [
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
] as ToolbarNames[];

if (props.extraToolbarsExclude) {
    toolbarsExclude.push(...props.extraToolbarsExclude);
}

const onUploadImg = async (
    files: File[],
    callback: (urls: string[]) => void,
) => {
    try {
        const formData = new FormData();

        // Append all the files to FormData
        files.forEach((file) => formData.append('image', file));

        // Send POST request to backend with image data
        const response = await axios.post(editor.upload().url, formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });

        // Callback to update the editor with the uploaded image URL
        callback([response.data.url]);
    } catch (error) {
        console.error('Image upload failed:', error);
    }
};
</script>

<template>
    <Field
        :class="
            cn(
                'mx-auto w-full max-w-[300px] sm:max-w-[600px] md:max-w-full',
                props.class,
            )
        "
        :data-invalid="!!props.error"
    >
        <FieldLabel v-if="props.label" :for="props.id">
            {{ props.label }}
        </FieldLabel>

        <MdEditor
            v-model="model"
            :preview="false"
            :toolbars-exclude="toolbarsExclude"
            language="es-ES"
            noImgZoomIn
            @onChange="emit('validate')"
            @onUploadImg="onUploadImg"
        />

        <FieldDescription v-if="props.description">
            {{ props.description }}
        </FieldDescription>

        <FieldError v-if="props.error" :errors="[props.error]" />
    </Field>
</template>
