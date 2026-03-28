import type { RouteDefinition } from '@/wayfinder';
import { router } from '@inertiajs/vue3';
import { useTimeoutFn } from '@vueuse/core';
import { ref } from 'vue';

type BuildUrl<T> = (item: T) => RouteDefinition<'get'>;

interface UsePrefetchOptions {
    delay?: number;
    method?: 'get' | 'post' | 'put' | 'patch' | 'delete';
    successId?: number | null;
}

export function usePrefetch<T extends { id: number }>(
    buildUrl: BuildUrl<T>,
    options: UsePrefetchOptions = {},
) {
    const { delay = 75, method = 'get', successId = null } = options;

    const prefetched = new Set<T['id']>();
    const pendingItem = ref<T | null>(null);

    function flushPrefetchedRow() {
        if (!successId) return;
        prefetched.delete(successId);
        router.flush(buildUrl({ id: successId } as T));
    }

    function onClick(item: T) {
        flushPrefetchedRow();

        router.get(
            buildUrl(item),
            {},
            {
                preserveState: false,
                replace: true,
            },
        );
    }

    const { start, stop } = useTimeoutFn(
        () => {
            if (!pendingItem.value) return;

            const id = pendingItem.value.id;
            if (prefetched.has(id)) return;

            router.prefetch(buildUrl(pendingItem.value), {
                method,
            });

            prefetched.add(id);
            pendingItem.value = null;
        },
        delay,
        { immediate: false },
    );

    function onHover(item: T) {
        if (prefetched.has(item.id)) return;

        pendingItem.value = item;
        start();
    }

    function onLeave() {
        stop();
        pendingItem.value = null;
    }

    return {
        onClick,
        onHover,
        onLeave,
        prefetched,
    };
}
