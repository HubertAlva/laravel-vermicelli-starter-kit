<script setup lang="ts">
import { Head, router, setLayoutProps, useForm } from '@inertiajs/vue3';
import { ExternalLink, RefreshCcw, Trash } from '@lucide/vue';
import FormHeader from '@/components/admin/FormHeader.vue';
import PostForm from '@/components/admin/PostForm.vue';
import AppTooltip from '@/components/AppTooltip.vue';
import SubmitButton from '@/components/SubmitButton.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { usePermissions } from '@/composables/usePermissions';
import { useUnsavedChanges } from '@/composables/useUnsavedChanges';
import { cn, formatDateTime, truncateText } from '@/lib/utils';
import posts from '@/routes/admin/posts';
import blog from '@/routes/blog';
import { confirmDelete } from '@/table/actions';

const props = defineProps<{
    post: App.Data.PostData;
}>();

setLayoutProps({
    breadcrumbs: [
        { title: 'Artículos', href: posts.index() },
        {
            title: truncateText(props.post.name, 30),
            href: posts.edit(props.post.id),
        },
    ],
});

const { can } = usePermissions();

const form = useForm<App.Data.PostFormData>({
    name: props.post.name,
    excerpt: props.post.excerpt ?? '',
    content: props.post.content ?? '',
    thumbnail: props.post.thumbnail,
    is_new_thumbnail: false,
    published: !!props.post.published_at,
    tags: props.post.tags?.map((tag) => tag.name) ?? [],
})
    .withPrecognition(posts.update(props.post.id))
    .setValidationTimeout(500)
    .validateFiles();

function submit(): void {
    // Remount with the saved post so the form starts from the stored values...
    form.submit({
        preserveState: false,
        preserveScroll: true,
        invalidateCacheTags: 'posts',
    });
}

function restore(): void {
    router.post(
        posts.restore(props.post.id),
        {},
        { invalidateCacheTags: 'posts' },
    );
}

function remove(): void {
    if (props.post.deleted_at) {
        confirmDelete(posts.forceDestroy(props.post.id), {
            message: 'El artículo se eliminará definitivamente. ¿Continuar?',
            cacheTag: 'posts',
        });
    } else {
        confirmDelete(posts.destroy(props.post.id), {
            message: '¿Mover el artículo a la papelera?',
            cacheTag: 'posts',
        });
    }
}

useUnsavedChanges(() => form.isDirty);
</script>

<template>
    <Head :title="`Editar ${post.name}`" />

    <form
        :class="cn(form.processing && 'pointer-events-none opacity-50')"
        @submit.prevent="submit"
    >
        <FormHeader :title="post.name">
            <template #title>
                <a
                    v-if="post.published_at && !post.deleted_at"
                    :href="blog.show(post.slug).url"
                    target="_blank"
                    class="group flex items-center gap-2"
                >
                    <h1 class="text-2xl font-semibold group-hover:underline">
                        {{ truncateText(post.name, 40) }}
                    </h1>
                    <ExternalLink class="size-5 text-muted-foreground" />
                </a>
                <h1 v-else class="text-2xl font-semibold">
                    {{ truncateText(post.name, 40) }}
                </h1>
            </template>

            <template #badges>
                <Badge :variant="form.published ? 'success' : 'warn'">
                    {{ form.published ? 'Publicado' : 'Borrador' }}
                </Badge>
                <Badge v-if="post.deleted_at" variant="destructive">
                    En papelera desde {{ formatDateTime(post.deleted_at) }}
                </Badge>
            </template>

            <template #actions>
                <Button
                    v-if="post.deleted_at && can('posts.restore')"
                    type="button"
                    variant="secondary"
                    @click="restore"
                >
                    <RefreshCcw class="size-4" />
                    Restaurar
                </Button>

                <AppTooltip
                    v-if="
                        can(
                            post.deleted_at
                                ? 'posts.forceDelete'
                                : 'posts.delete',
                        )
                    "
                    :text="
                        post.deleted_at
                            ? 'Eliminar definitivamente'
                            : 'Mover a la papelera'
                    "
                >
                    <Button
                        type="button"
                        size="icon"
                        variant="destructive"
                        :aria-label="
                            post.deleted_at
                                ? 'Eliminar definitivamente'
                                : 'Mover a la papelera'
                        "
                        @click="remove"
                    >
                        <Trash />
                    </Button>
                </AppTooltip>

                <SubmitButton :processing="form.processing"
                    >Guardar</SubmitButton
                >
            </template>
        </FormHeader>

        <PostForm :form />
    </form>
</template>
