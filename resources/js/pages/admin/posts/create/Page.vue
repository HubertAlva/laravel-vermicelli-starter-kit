<script lang="ts" setup>
import {
    ImageInput,
    MarkdownInput,
    SwitchInput,
    TagsListboxInput,
    TextInput,
} from '@/components/form';
import { Button } from '@/components/ui/button';
import {
    Field,
    FieldDescription,
    FieldGroup,
    FieldLegend,
    FieldSet,
} from '@/components/ui/field';
import { Spinner } from '@/components/ui/spinner';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { cn, validate } from '@/lib/utils';
import posts from '@/routes/admin/posts';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const title = 'Crear artículo';

const breadcrumb = [
    {
        name: title,
    },
];
const form = useForm({
    name: 'Hubert Alva',
    content: '',
    is_published: false,
    thumbnail: null,
    is_new_thumbnail: false,
    tags: [1],
}).withPrecognition('post', posts.store().url);

const submit = () => form.submit();

form.validateFiles();

const validateField = validate(form);

const currentThumbnailUrl = ref(form.thumbnail);

const clearError = (field: string) => {
    const key = field as keyof typeof form.errors;
    if (form.errors[key]) {
        form.clearErrors(key);
    }
};

const options = [
    { name: 'Next.js', id: 1, created_at: '2021-01-01' },
    { name: 'SvelteKit', id: 2, created_at: '2021-01-01' },
    { name: 'Nuxt', id: 3, created_at: '2021-01-01' },
    { name: 'Remix', id: 4, created_at: '2021-01-01' },
    { name: 'Astro', id: 5, created_at: '2021-01-01' },
];
</script>

<template>
    <AdminLayout :breadcrumb="breadcrumb" :title="title" container="small">
        <form
            :class="cn(form.processing ? 'pointer-events-none opacity-50' : '')"
            @submit.prevent="submit"
        >
            <FieldGroup>
                <FieldSet>
                    <FieldLegend>Payment Method</FieldLegend>
                    <FieldDescription>
                        All transactions are secure and encrypted
                    </FieldDescription>
                    <FieldGroup>
                        <TextInput
                            id="name"
                            v-model="form.name"
                            :error="form.errors.name"
                            label="Nombre"
                            placeholder="Nombre completo"
                            type="text"
                            @change="validateField('name')"
                        />

                        <TagsListboxInput
                            v-model="form.tags"
                            :options="options"
                            label="Frameworks"
                        />

                        <SwitchInput
                            id="is_published"
                            v-model="form.is_published"
                            :error="form.errors.is_published"
                            @change="validateField('is_published')"
                        />

                        <MarkdownInput
                            v-model="form.content"
                            :error="form.errors.content"
                            label="Contenido"
                            @validate="validateField('content')"
                        />

                        <ImageInput
                            v-model="form.thumbnail"
                            v-model:isNewImage="form.is_new_thumbnail"
                            :currentImageUrl="currentThumbnailUrl"
                            :error="form.errors.thumbnail"
                            description="PNG, JPG, WEBP (máx. 20 MB)"
                            label="Thumbnail"
                            @clearErrors="clearError('thumbnail')"
                            @validate="validateField('thumbnail')"
                        />
                    </FieldGroup>
                </FieldSet>

                <Field orientation="horizontal">
                    <Spinner v-if="form.validating" />
                    <Button :disabled="form.processing" type="submit">
                        Submit
                    </Button>
                    <Button type="button" variant="outline"> Cancel </Button>
                </Field>
            </FieldGroup>
        </form>
    </AdminLayout>
</template>
