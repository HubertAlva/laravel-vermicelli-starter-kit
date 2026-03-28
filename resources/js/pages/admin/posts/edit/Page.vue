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
import { useSoftDelete } from '@/composables/useSoftDelete';
import { useUnsavedChanges } from '@/composables/useUnsavedChanges';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { cn, formatDateTime, truncateText } from '@/lib/utils';
import Tooltip from '@/plugins/Tooltip.vue';
import posts from '@/routes/admin/posts';
import blog from '@/routes/blog';
import { useForm } from '@inertiajs/vue3';
import { ExternalLink, RefreshCcw, Trash } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    post: App.Data.PostData;
}>();

const parentTitle = 'Artículos';
const title = 'Editar artículo ' + props.post.name;
const defaultTitle = props.post.name;
const buttonLabel = 'Guardar';

const breadcrumb = [
    {
        name: parentTitle,
        href: posts.index().url,
    },
    {
        name: title,
    },
];

const tags = props.post.tags?.map((tag) => tag.name) ?? [];

const form = useForm<App.Data.PostFormData>({
    name: props.post.name,
    excerpt: props.post.excerpt,
    content: props.post.content,
    thumbnail: props.post.thumbnail,
    published_at: !!props.post.published_at,
    is_new_thumbnail: false,
    deleted_at: props.post.deleted_at ?? null,
    tags: tags,
}).withPrecognition(posts.update(props.post.id));

const submit = () =>
    form.submit({
        preserveState: false,
    });

form.validateFiles();

const publishLabel = computed(() => {
    return form.published_at ? 'Publicar' : 'Borrador';
});

const deleteTooltip = computed(() => {
    return form.deleted_at !== null ? 'Eliminar' : 'Mover a papelera';
});

const { restore, remove } = useSoftDelete({
    destroy: (id) => posts.destroy(id).url,
    softDelete: (id) => posts.softDelete(id).url,
    restore: (id) => posts.restore(id).url,
});

useUnsavedChanges(() => form.isDirty);
</script>

<template>
    <AdminLayout :breadcrumb="breadcrumb" :title="title" container="full">
        <div>
            <FormHeaderBase>
                <Heading>
                    <a
                        :href="blog.show(post.slug).url"
                        target="_blank"
                        class="group flex items-center justify-start gap-2"
                    >
                        <HeadingTitle
                            class="group-hover:text-blue-500 group-hover:underline"
                        >
                            {{ truncateText(defaultTitle, 35) }}
                        </HeadingTitle>

                        <ExternalLink
                            class="size-5 text-muted-foreground group-hover:text-blue-500"
                        />
                    </a>

                    <Badge :variant="form.published_at ? 'success' : 'warn'">
                        {{ publishLabel }}
                    </Badge>

                    <Badge
                        v-if="form.deleted_at !== null"
                        variant="destructive"
                    >
                        Eliminado el
                        {{ formatDateTime(form.deleted_at) }}
                    </Badge>
                </Heading>

                <HeaderActions>
                    <Button
                        v-if="form.deleted_at !== null"
                        type="button"
                        variant="secondary"
                        @click="restore(props.post.id)"
                    >
                        <RefreshCcw class="mr-2 h-4 w-4" />
                        Restaurar
                    </Button>

                    <Tooltip :text="deleteTooltip">
                        <Button
                            size="icon"
                            type="button"
                            variant="destructive"
                            @click="
                                remove(props.post.id, form.deleted_at !== null)
                            "
                        >
                            <Trash />
                        </Button>
                    </Tooltip>

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
