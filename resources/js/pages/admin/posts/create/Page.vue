<script lang="ts" setup>
import {
    FormHeaderBase,
    HeaderActions,
    Heading,
    HeadingTitle,
} from '@/components/form-header';
import PostForm from '@/components/form/PostForm.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import { useUnsavedChanges } from '@/composables/useUnsavedChanges';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { cn, truncateText } from '@/lib/utils';
import posts from '@/routes/admin/posts';
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const parentTitle = 'Artículos';
const title = 'Crear artículo';
const defaultTitle = 'Nuevo artículo';
const buttonLabel = 'Crear';

const breadcrumb = [
    {
        name: parentTitle,
        href: posts.index().url,
    },
    {
        name: title,
    },
];

const form = useForm<App.Data.PostFormData>({
    name: undefined,
    excerpt: undefined,
    content: undefined,
    thumbnail: null,
    published_at: true,
    is_new_thumbnail: false,
    deleted_at: null,
    tags: null,
}).withPrecognition(posts.store());

const submit = () => form.submit();

form.validateFiles();

const publishLabel = computed(() => {
    return form.published_at ? 'Publicar' : 'Borrador';
});

useUnsavedChanges(() => form.isDirty);
</script>

<template>
    <AdminLayout :breadcrumb="breadcrumb" :title="title" container="full">
        <div>
            <FormHeaderBase>
                <Heading>
                    <HeadingTitle>
                        {{
                            truncateText(
                                form.name ? form.name : defaultTitle,
                                35,
                            )
                        }}
                    </HeadingTitle>

                    <Badge :variant="form.published_at ? 'success' : 'warn'">
                        {{ publishLabel }}
                    </Badge>
                </Heading>

                <HeaderActions>
                    <Button
                        type="submit"
                        @click="submit"
                        :disabled="form.processing || form.validating"
                    >
                        {{ buttonLabel }}
                        <Spinner v-if="form.validating || form.processing" />
                    </Button>
                </HeaderActions>
            </FormHeaderBase>

            <form
                :class="
                    cn(
                        'grid gap-4 md:grid-cols-2',
                        form.processing ? 'pointer-events-none opacity-50' : '',
                    )
                "
                @submit.prevent="submit"
            >
                <PostForm :buttonLabel="buttonLabel" :form="form" />
            </form>
        </div>
    </AdminLayout>
</template>
