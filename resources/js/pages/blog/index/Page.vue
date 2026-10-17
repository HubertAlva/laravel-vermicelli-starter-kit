<script setup lang="ts">
import { Deferred, Link } from '@inertiajs/vue3';
import { ArrowUpRight, FolderCode } from '@lucide/vue';
import PostCard from '@/components/blog/PostCard.vue';
import { Container } from '@/components/container';
import Paginator from '@/components/Paginator.vue';
import Seo from '@/components/Seo.vue';
import { Button } from '@/components/ui/button';
import {
    Empty,
    EmptyDescription,
    EmptyHeader,
    EmptyMedia,
    EmptyTitle,
} from '@/components/ui/empty';
import { Skeleton } from '@/components/ui/skeleton';
import { home } from '@/routes';
import type { Paginated } from '@/types';

defineProps<{
    posts?: Paginated<App.Data.PostData>;
}>();
</script>

<template>
    <Seo title="Blog" description="Artículos y novedades." />

    <section class="bg-linear-to-b from-muted to-background">
        <Container as="div">
            <h1 class="text-4xl font-extrabold tracking-tighter sm:text-5xl">
                Blog
            </h1>
            <p class="max-w-2xl text-lg text-muted-foreground">
                Artículos, novedades y guías.
            </p>
        </Container>
    </section>

    <Container>
        <Deferred data="posts">
            <template #fallback>
                <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                    <Skeleton
                        v-for="n in 6"
                        :key="n"
                        class="aspect-[4/5] rounded-xl"
                    />
                </div>
            </template>

            <div v-if="posts?.data.length" class="space-y-8">
                <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                    <PostCard v-for="post in posts.data" :key="post.id" :post />
                </div>

                <Paginator :meta="posts.meta" :only="['posts']" />
            </div>

            <Empty v-else>
                <EmptyHeader>
                    <EmptyMedia variant="icon">
                        <FolderCode />
                    </EmptyMedia>
                    <EmptyTitle>No hay artículos disponibles.</EmptyTitle>
                    <EmptyDescription
                        >Todavía no se han publicado
                        artículos.</EmptyDescription
                    >
                </EmptyHeader>
                <Button
                    as-child
                    size="sm"
                    variant="link"
                    class="text-muted-foreground"
                >
                    <Link :href="home()">
                        Regresar al inicio
                        <ArrowUpRight />
                    </Link>
                </Button>
            </Empty>
        </Deferred>
    </Container>
</template>
