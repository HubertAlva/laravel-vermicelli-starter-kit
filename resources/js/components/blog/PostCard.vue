<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowRight, Image } from '@lucide/vue';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent, CardFooter } from '@/components/ui/card';
import { formatDate } from '@/lib/utils';
import blog from '@/routes/blog';

defineProps<{
    post: App.Data.PostData;
}>();
</script>

<template>
    <Link :href="blog.show(post.slug)" class="group block h-full" prefetch>
        <Card
            class="h-full gap-0 overflow-hidden py-0 transition-shadow hover:shadow-md"
        >
            <div class="relative aspect-video overflow-hidden bg-muted">
                <img
                    v-if="post.thumbnail"
                    :src="post.thumbnail"
                    :alt="post.name"
                    loading="lazy"
                    class="size-full object-cover transition-transform duration-500 group-hover:scale-105"
                />
                <div v-else class="flex size-full items-center justify-center">
                    <Image class="text-muted-foreground" />
                </div>
            </div>

            <CardContent class="flex-1 p-5">
                <div
                    class="mb-3 flex flex-wrap items-center justify-between gap-2"
                >
                    <div class="flex flex-wrap gap-2">
                        <Badge
                            v-for="tag in post.tags"
                            :key="tag.id"
                            variant="secondary"
                            class="text-[10px] tracking-wider uppercase"
                        >
                            {{ tag.name }}
                        </Badge>
                    </div>
                    <time
                        :datetime="post.published_at ?? undefined"
                        class="text-xs text-muted-foreground"
                    >
                        {{ formatDate(post.published_at, { month: 'short' }) }}
                    </time>
                </div>

                <h3 class="mb-2 line-clamp-2 text-xl font-bold tracking-tight">
                    {{ post.name }}
                </h3>
                <p class="line-clamp-3 text-sm text-muted-foreground">
                    {{ post.excerpt }}
                </p>
            </CardContent>

            <CardFooter
                class="gap-4 px-5 pb-5 text-xs text-muted-foreground uppercase"
            >
                Leer más
                <div class="h-px flex-1 bg-border" />
                <ArrowRight
                    class="size-4 transition-transform group-hover:translate-x-1"
                />
            </CardFooter>
        </Card>
    </Link>
</template>
