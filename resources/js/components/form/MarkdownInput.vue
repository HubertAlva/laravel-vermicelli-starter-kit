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
import { config, MdEditor, type ToolbarNames } from 'md-editor-v3';
import 'md-editor-v3/lib/style.css';
import type { HTMLAttributes } from 'vue';

interface Props {
    label?: string;
    id: string;
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

const getXsrfToken = () => {
    const match = document.cookie
        .split('; ')
        .find((row) => row.startsWith('XSRF-TOKEN='));

    return match ? decodeURIComponent(match.split('=')[1]) : '';
};

const onUploadImg = async (
    files: File[],
    callback: (urls: string[]) => void,
) => {
    try {
        const xsrfToken = getXsrfToken();

        const uploads = files.map(async (file) => {
            const formData = new FormData();
            formData.append('image', file);

            const response = await fetch(editor.upload().url, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin',
                headers: {
                    'X-XSRF-TOKEN': xsrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (!response.ok) {
                const text = await response.text();
                throw new Error(`Upload failed: ${response.status} - ${text}`);
            }

            const data = await response.json();

            if (!data.url) {
                throw new Error('Invalid response: missing URL');
            }

            return data.url;
        });

        const urls = await Promise.all(uploads);
        callback(urls);
    } catch (error) {
        console.error('Image upload failed:', error);
    }
};
</script>

<template>
    <Field
        :class="
            cn(
                'mx-auto w-full max-w-75 sm:max-w-150 md:max-w-full',
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
