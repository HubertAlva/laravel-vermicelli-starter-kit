<script lang="ts" setup>
import { Container } from '@/components/container';
import { Badge } from '@/components/ui/badge';
import AppLayout from '@/layouts/AppLayout.vue';
import { formatDate, renderMarkdown } from '@/lib/utils';

const props = defineProps<{
    post: App.Data.PostData;
}>();

const head = {
    title: props.post.name,
    description: props.post.excerpt,
    image: props.post.thumbnail,
};
</script>

<template>
    <AppLayout :head="head">
        <Container class="max-w-2xl">
            <div class="mx-auto prose">
                <h1>
                    {{ props.post.name }}
                </h1>

                <p class="text-muted-foreground italic">
                    Artículo publicado el
                    {{ formatDate(props.post.created_at) }}
                </p>

                <div v-html="renderMarkdown(props.post.content)" />
            </div>
            <div
                v-if="props.post.tags"
                class="flex flex-wrap items-center justify-start gap-2"
            >
                <Badge v-for="tag in props.post.tags" :key="tag.id">
                    {{ tag.name }}
                </Badge>
            </div>
        </Container>
    </AppLayout>
</template>
