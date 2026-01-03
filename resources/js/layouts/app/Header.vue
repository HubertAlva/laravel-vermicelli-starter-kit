<script lang="ts" setup>
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
import { home } from '@/routes';
import { Link, usePage } from '@inertiajs/vue3';
import { useWindowScroll } from '@vueuse/core';
import { Menu } from 'lucide-vue-next';
import { ref, watch } from 'vue';

const { y } = useWindowScroll();
const previousScroll = ref(0);
const scrollingUp = ref(false);

const page = usePage();

watch(y, (value, prevValue) => {
    scrollingUp.value = value <= prevValue;
    previousScroll.value = prevValue;
});
</script>

<template>
    <header
        :class="
            cn(
                'fixed top-0 z-50 flex min-h-[60px] w-full items-center justify-center border-b bg-white transition-all duration-300 xl:min-h-[68px]',
                {
                    'border-none shadow-[0_2px_4px_1px_rgba(0,0,0,0.2)]': y > 0,
                    '-translate-y-[100%]': y > 150 && !scrollingUp,
                    '-translate-y-0': scrollingUp,
                },
            )
        "
    >
        <nav class="flex w-full max-w-7xl items-center justify-between px-5">
            <Link :href="home().url">
                <img
                    alt="Abstract"
                    class="max-w-28 md:max-w-[150px]"
                    src="/images/logo.png"
                />
            </Link>

            <div class="hidden items-center justify-end gap-12 lg:flex">
                <ul
                    class="flex items-center justify-center gap-8 text-sm font-medium xl:gap-12 xl:text-[0.9375rem]"
                >
                    <li v-for="(item, index) in headerNavLinks" :key="index">
                        <Link
                            :class="
                                cn(
                                    'text-primary hover:text-blue-500',
                                    page.component.startsWith(
                                        item.componentRoot || '',
                                    ) && 'text-blue-500',
                                )
                            "
                            :href="item.url"
                        >
                            {{ item.name }}
                        </Link>
                    </li>
                </ul>
            </div>

            <div class="lg:hidden">
                <Sheet>
                    <SheetTrigger>
                        <Button size="icon" variant="outline">
                            <Menu class="h-4 w-4" />
                        </Button>
                    </SheetTrigger>
                    <SheetContent>
                        <SheetHeader class="gap-y-0">
                            <SheetTitle></SheetTitle>
                            <SheetDescription>
                                <div class="py-8">
                                    <nav class="space-y-4">
                                        <ul
                                            class="space-y-2 text-start text-base font-medium"
                                        >
                                            <li
                                                v-for="(
                                                    item, index
                                                ) in headerNavLinks"
                                                :key="index"
                                                :class="
                                                    cn(
                                                        'flex min-h-8 w-full items-center justify-start text-primary',
                                                        page.component.startsWith(
                                                            item.componentRoot,
                                                        ) && 'text-blue-500',
                                                    )
                                                "
                                            >
                                                <Link
                                                    :href="item.url"
                                                    class="w-full"
                                                >
                                                    {{ item.name }}
                                                </Link>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </SheetDescription>
                        </SheetHeader>
                    </SheetContent>
                </Sheet>
            </div>
        </nav>
    </header>
</template>
