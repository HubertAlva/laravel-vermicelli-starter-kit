import { router } from '@inertiajs/vue3';
import { useTimeoutFn } from '@vueuse/core';
import type { RouteDefinition } from '@/wayfinder';

export type UseRowPrefetchReturn = {
    onClick: (row: { id: number }) => void;
    onHover: (row: { id: number }) => void;
    onLeave: () => void;
};

/**
 * Prefetch a row's page after hovering it briefly, and visit it on click.
 *
 * Prefetched pages are tagged with `cacheTag`; pass the same tag as
 * `invalidateCacheTags` when saving or deleting so stale pages are dropped.
 */
export function useRowPrefetch(
    href: (row: { id: number }) => RouteDefinition<'get'>,
    cacheTag: string,
    delay = 75,
): UseRowPrefetchReturn {
    let pending: { id: number } | null = null;

    const { start, stop } = useTimeoutFn(
        () => {
            if (pending) {
                router.prefetch(
                    href(pending),
                    {},
                    { cacheFor: '30s', cacheTags: cacheTag },
                );
            }
        },
        delay,
        { immediate: false },
    );

    return {
        onClick: (row) => router.visit(href(row)),
        onHover: (row) => {
            pending = row;
            start();
        },
        onLeave: () => {
            stop();
            pending = null;
        },
    };
}
