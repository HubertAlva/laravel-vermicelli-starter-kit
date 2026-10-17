<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import FormHeader from '@/components/admin/FormHeader.vue';
import PostForm from '@/components/admin/PostForm.vue';
import SubmitButton from '@/components/SubmitButton.vue';
import { Badge } from '@/components/ui/badge';
import { useUnsavedChanges } from '@/composables/useUnsavedChanges';
import { cn, truncateText } from '@/lib/utils';
import posts from '@/routes/admin/posts';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Artículos', href: posts.index() },
            { title: 'Crear artículo', href: posts.create() },
        ],
    },
});

const form = useForm<App.Data.PostFormData>({
    name: '',
    excerpt: '',
    content: '',
    thumbnail: null,
    is_new_thumbnail: false,
    published: true,
    tags: null,
})
    .withPrecognition(posts.store())
    .setValidationTimeout(500)
    .validateFiles();

function submit(): void {
    form.submit({ invalidateCacheTags: 'posts' });
}

useUnsavedChanges(() => form.isDirty);
</script>

<template>
    <Head title="Crear artículo" />

    <form
        :class="cn(form.processing && 'pointer-events-none opacity-50')"
        @submit.prevent="submit"
    >
        <FormHeader :title="truncateText(form.name || 'Nuevo artículo', 40)">
            <template #badges>
                <Badge :variant="form.published ? 'success' : 'warn'">
                    {{ form.published ? 'Publicado' : 'Borrador' }}
                </Badge>
            </template>

            <template #actions>
                <SubmitButton :processing="form.processing">Crear</SubmitButton>
            </template>
        </FormHeader>

        <PostForm :form />
    </form>
</template>
