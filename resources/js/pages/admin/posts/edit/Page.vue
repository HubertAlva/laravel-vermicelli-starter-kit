<script lang="ts" setup>
import PostForm from '@/components/form/PostForm.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { useSoftDelete } from '@/composables/useSoftDelete';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { cn, truncateText } from '@/lib/utils';
import Tooltip from '@/plugins/Tooltip.vue';
import posts from '@/routes/admin/posts';
import { useForm } from '@inertiajs/vue3';
import { RefreshCcw, Trash } from 'lucide-vue-next';
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

const tags = props.post.tags?.map((tag) => tag.name);

const form = useForm<App.Data.FormPostData>({
    name: props.post.name,
    excerpt: props.post.excerpt,
    content: props.post.content,
    thumbnail: props.post.thumbnail,
    published_at: !!props.post.published_at,
    is_new_thumbnail: false,
    deleted_at: props.post.deleted_at ?? null,
    tags: tags,
}).withPrecognition('post', posts.update(props.post.id).url);

const submit = () => form.submit();

form.validateFiles();

const publishLabel = computed(() => {
    return form.published_at ? 'Publicar' : 'Borrador';
});

const deleteTooltip = computed(() => {
    return form.deleted_at !== null ? 'Eliminar' : 'Mover a papelera';
});

const { restore, remove } = useSoftDelete({
    restore: (id) => posts.restore(id).url,
    destroy: (id) => posts.destroy(id).url,
    softDelete: (id) => posts.softDelete(id).url,
});

window.addEventListener('beforeunload', (event) => {
    if (form.isDirty) {
        event.preventDefault();
        event.returnValue = '';
    }
});
</script>

<template>
    <AdminLayout :breadcrumb="breadcrumb" :title="title" container="full">
        <div>
            <div
                class="sticky top-0 z-10 -mt-4 flex flex-wrap items-center justify-between gap-4 bg-muted py-4"
            >
                <div class="flex flex-wrap items-center justify-start gap-2">
                    <div class="flex items-center justify-start gap-2">
                        <h1 class="text-2xl font-semibold">
                            {{
                                truncateText(
                                    form.name ? form.name : defaultTitle,
                                    35,
                                )
                            }}
                        </h1>
                    </div>

                    <Badge :variant="form.published_at ? 'success' : 'warn'">
                        {{ publishLabel }}
                    </Badge>

                    <Badge
                        v-if="form.deleted_at !== null"
                        variant="destructive"
                    >
                        Eliminado el
                        {{
                            new Date(form.deleted_at).toLocaleDateString(
                                'es-PE',
                            )
                        }}
                    </Badge>
                </div>

                <div class="flex flex-wrap items-center justify-end gap-2">
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

                    <Button type="submit" @click="submit">
                        {{ buttonLabel }}
                    </Button>
                </div>
            </div>

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
