<script lang="ts" setup>
import { Badge } from '@/components/ui/badge';
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
} from '@/components/ui/card';
import { formatDate } from '@/lib/utils';
import { show } from '@/routes/blog';
import { Link } from '@inertiajs/vue3';
import { ArrowRight, Image } from 'lucide-vue-next';

defineProps<{
    post: App.Data.PostData;
}>();
</script>

<template>
    <Link :href="show(post.slug).url">
        <Card
            class="group overflow-hidden border-muted/40 bg-card/50 backdrop-blur-sm transition-all duration-300 hover:shadow-md"
        >
            <CardHeader class="p-0">
                <div class="relative aspect-video overflow-hidden">
                    <img
                        v-if="post.thumbnail"
                        :alt="post.name"
                        :src="post.thumbnail"
                        class="object-cover transition-transform duration-500 group-hover:scale-105"
                    />

                    <div
                        v-else
                        class="flex h-full items-center justify-center bg-neutral-100"
                    >
                        <Image />
                    </div>
                </div>
            </CardHeader>
            <CardContent class="p-5">
                <div
                    class="mb-3 flex flex-wrap items-center justify-between gap-2"
                >
                    <div class="flex flex-wrap gap-2">
                        <Badge
                            v-for="tag in post.tags"
                            :key="tag.id"
                            class="border-none bg-muted/50 px-2 py-0 text-[10px] font-semibold tracking-wider text-muted-foreground uppercase"
                            variant="secondary"
                        >
                            {{ tag.name }}
                        </Badge>
                    </div>
                    <time
                        class="text-[10px] font-medium tracking-widest text-muted-foreground/60 uppercase"
                    >
                        {{ formatDate(post.created_at, 'short') }}
                    </time>
                </div>
                <h3
                    class="mb-2 line-clamp-1 text-xl font-bold tracking-tight transition-colors group-hover:text-primary"
                >
                    {{ post.name }}
                </h3>
                <p
                    class="line-clamp-2 text-sm leading-relaxed text-muted-foreground"
                >
                    {{ post.excerpt }}
                </p>
            </CardContent>
            <CardFooter
                class="flex items-center justify-between px-5 pt-0 pb-5"
            >
                <span
                    class="text-[10px] font-medium tracking-widest text-muted-foreground/60 uppercase"
                >
                    Ver más
                </span>
                <div class="mx-4 h-px flex-1 bg-border/40" />

                <ArrowRight
                    class="size-4 text-muted-foreground transition-transform group-hover:translate-x-1"
                />
            </CardFooter>
        </Card>
    </Link>
</template>
