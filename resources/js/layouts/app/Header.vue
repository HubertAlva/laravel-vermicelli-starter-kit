<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { Menu } from '@lucide/vue';
import { useMounted, useWindowScroll } from '@vueuse/core';
import { computed, ref, watch } from 'vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import ThemeSwitcher from '@/components/ThemeSwitcher.vue';
import { Button } from '@/components/ui/button';
import {
    Sheet,
    SheetContent,
    SheetDescription,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from '@/components/ui/sheet';
import { headerNavLinks } from '@/lib/navLinks';
import { cn } from '@/lib/utils';
import { dashboard, home, login, register } from '@/routes';
import admin from '@/routes/admin';

const page = usePage();
const user = computed(() => page.props.auth.user);

const { y: windowY } = useWindowScroll();

// Treat the page as unscrolled until hydrated, matching the server render...
const isMounted = useMounted();
const y = computed(() => (isMounted.value ? windowY.value : 0));
const scrollingUp = ref(true);

watch(y, (value, previousValue) => {
    scrollingUp.value = value <= previousValue;
});

function isActive(componentRoot?: string): boolean {
    return !!componentRoot && page.component.startsWith(componentRoot);
}
</script>

<template>
    <header
        :class="
            cn(
                'fixed top-0 z-50 flex min-h-15 w-full items-center justify-center border-b bg-background transition-all duration-300 xl:min-h-17',
                {
                    'border-transparent shadow-md': y > 0,
                    '-translate-y-full': y > 150 && !scrollingUp,
                },
            )
        "
    >
        <nav class="flex w-full max-w-6xl items-center justify-between px-5">
            <Link :href="home()" class="flex items-center gap-2 font-semibold">
                <AppLogoIcon class="size-7 fill-current" />
                <span class="hidden sm:inline">{{ page.props.name }}</span>
            </Link>

            <div class="hidden items-center gap-8 lg:flex">
                <ul class="flex items-center gap-8 text-sm font-medium">
                    <li v-for="item in headerNavLinks" :key="item.title">
                        <Link
                            :href="item.href"
                            :class="
                                cn(
                                    'transition-colors hover:text-primary/70',
                                    isActive(item.componentRoot) &&
                                        'text-primary underline underline-offset-8',
                                )
                            "
                        >
                            {{ item.title }}
                        </Link>
                    </li>
                </ul>

                <div class="flex items-center gap-2">
                    <ThemeSwitcher />

                    <template v-if="user">
                        <Button
                            v-if="page.props.auth.isAdmin"
                            as-child
                            variant="outline"
                        >
                            <a :href="admin.dashboard().url">Panel</a>
                        </Button>
                        <Button as-child>
                            <Link :href="dashboard()">Mi cuenta</Link>
                        </Button>
                    </template>
                    <template v-else>
                        <Button as-child variant="ghost">
                            <Link :href="login()">Iniciar sesión</Link>
                        </Button>
                        <Button as-child>
                            <Link :href="register()">Registrarse</Link>
                        </Button>
                    </template>
                </div>
            </div>

            <div class="flex items-center gap-2 lg:hidden">
                <ThemeSwitcher />

                <Sheet>
                    <SheetTrigger as-child>
                        <Button size="icon" variant="outline" aria-label="Menú">
                            <Menu class="size-4" />
                        </Button>
                    </SheetTrigger>
                    <SheetContent>
                        <SheetHeader>
                            <SheetTitle>{{ page.props.name }}</SheetTitle>
                            <SheetDescription class="sr-only">
                                Navegación principal
                            </SheetDescription>
                        </SheetHeader>

                        <nav
                            class="flex flex-col gap-1 px-4 text-base font-medium"
                        >
                            <Link
                                v-for="item in headerNavLinks"
                                :key="item.title"
                                :href="item.href"
                                :class="
                                    cn(
                                        'rounded-md px-2 py-2 hover:bg-accent',
                                        isActive(item.componentRoot) &&
                                            'bg-accent',
                                    )
                                "
                            >
                                {{ item.title }}
                            </Link>

                            <template v-if="user">
                                <a
                                    v-if="page.props.auth.isAdmin"
                                    :href="admin.dashboard().url"
                                    class="rounded-md px-2 py-2 hover:bg-accent"
                                >
                                    Panel
                                </a>
                                <Link
                                    :href="dashboard()"
                                    class="rounded-md px-2 py-2 hover:bg-accent"
                                >
                                    Mi cuenta
                                </Link>
                            </template>
                            <template v-else>
                                <Link
                                    :href="login()"
                                    class="rounded-md px-2 py-2 hover:bg-accent"
                                >
                                    Iniciar sesión
                                </Link>
                                <Link
                                    :href="register()"
                                    class="rounded-md px-2 py-2 hover:bg-accent"
                                >
                                    Registrarse
                                </Link>
                            </template>
                        </nav>
                    </SheetContent>
                </Sheet>
            </div>
        </nav>
    </header>
</template>
