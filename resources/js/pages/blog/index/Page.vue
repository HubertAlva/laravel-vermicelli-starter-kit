<script lang="ts" setup>
import { Container } from '@/components/container';
import Paginator from '@/components/Paginator.vue';
import PostCard from '@/components/PostCard.vue';
import { Button } from '@/components/ui/button';
import {
    Empty,
    EmptyDescription,
    EmptyHeader,
    EmptyMedia,
    EmptyTitle,
} from '@/components/ui/empty';
import { Spinner } from '@/components/ui/spinner';
import AppLayout from '@/layouts/AppLayout.vue';
import { home } from '@/routes';
import { Deferred, Link } from '@inertiajs/vue3';
import { ArrowUpRightIcon, FolderCode } from 'lucide-vue-next';

defineProps<{
    posts?: {
        data: App.Data.PostData[];
        links: App.Data.PaginatorLinkData[];
        meta: App.Data.PaginatorMetaData;
    };
}>();

const head = {
    title: 'Blog',
};
</script>

<template>
    <AppLayout :head="head">
        <section class="bg-gradient-to-b from-neutral-50 to-white">
            <Container as="div">
                <header>
                    <h1
                        class="mb-4 text-4xl font-extrabold tracking-tighter sm:text-5xl"
                    >
                        Blog
                    </h1>
                    <p class="max-w-2xl text-lg text-muted-foreground">
                        Lorem ipsum dolor sit amet, consectetur adipisicing
                        elit. Autem, ratione?
                    </p>
                </header>
            </Container>
        </section>

        <Container>
            <Deferred data="posts">
                <template #fallback>
                    <div class="flex h-48 items-center justify-center">
                        <Spinner class="size-12" />
                    </div>
                </template>

                <div v-if="posts?.data.length" class="space-y-4">
                    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                        <template v-for="post in posts?.data" :key="post.id">
                            <PostCard :post="post" />
                        </template>
                    </div>

                    <Paginator
                        :paginator="{
                            links: posts.links,
                            meta: posts.meta,
                        }"
                        label="posts"
                    />
                </div>

                <div v-else>
                    <Empty>
                        <EmptyHeader>
                            <EmptyMedia variant="icon">
                                <FolderCode />
                            </EmptyMedia>
                            <EmptyTitle>
                                No hay artículos disponibles.
                            </EmptyTitle>
                            <EmptyDescription>
                                Todavía no se han creado artículos.
                            </EmptyDescription>
                        </EmptyHeader>
                        <Button
                            as-child
                            class="text-muted-foreground"
                            size="sm"
                            variant="link"
                        >
                            <Link :href="home().url">
                                Regresar al inicio
                                <ArrowUpRightIcon />
                            </Link>
                        </Button>
                    </Empty>
                </div>
            </Deferred>
        </Container>
    </AppLayout>
</template>
