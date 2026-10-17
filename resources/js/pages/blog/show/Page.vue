<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Container } from '@/components/container';
import Seo from '@/components/Seo.vue';
import { Badge } from '@/components/ui/badge';
import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from '@/components/ui/breadcrumb';
import { renderMarkdown } from '@/lib/markdown';
import { formatDate, truncateText } from '@/lib/utils';
import { home } from '@/routes';
import blog from '@/routes/blog';

const props = defineProps<{
    post: App.Data.PostData;
}>();

const html = computed(() => renderMarkdown(props.post.content ?? ''));
</script>

<template>
    <Seo
        :title="post.name"
        :description="post.excerpt ?? undefined"
        :image="post.thumbnail ?? undefined"
        type="article"
    />

    <Container as="article" class="max-w-3xl">
        <Breadcrumb>
            <BreadcrumbList>
                <BreadcrumbItem>
                    <BreadcrumbLink as-child>
                        <Link :href="home()">Inicio</Link>
                    </BreadcrumbLink>
                </BreadcrumbItem>
                <BreadcrumbSeparator />
                <BreadcrumbItem>
                    <BreadcrumbLink as-child>
                        <Link :href="blog.index()">Blog</Link>
                    </BreadcrumbLink>
                </BreadcrumbItem>
                <BreadcrumbSeparator />
                <BreadcrumbItem>
                    <BreadcrumbPage>{{
                        truncateText(post.name, 50)
                    }}</BreadcrumbPage>
                </BreadcrumbItem>
            </BreadcrumbList>
        </Breadcrumb>

        <header class="space-y-2">
            <h1 class="text-4xl font-extrabold tracking-tight">
                {{ post.name }}
            </h1>
            <p class="text-muted-foreground">
                Publicado el
                <time :datetime="post.published_at ?? undefined">
                    {{ formatDate(post.published_at) }}
                </time>
            </p>
        </header>

        <img
            v-if="post.thumbnail"
            :src="post.thumbnail"
            :alt="post.name"
            class="w-full rounded-xl object-cover"
        />

        <!-- Content is authored by admins only, so rendering raw HTML is acceptable -->
        <div class="prose max-w-none dark:prose-invert" v-html="html" />

        <div v-if="post.tags?.length" class="flex flex-wrap gap-2">
            <Badge v-for="tag in post.tags" :key="tag.id" variant="secondary">
                {{ tag.name }}
            </Badge>
        </div>
    </Container>
</template>
