import { router } from '@inertiajs/vue3';
import type { RouteDefinition } from '@/wayfinder';

/**
 * Ask for confirmation, then send a DELETE request. The server redirects
 * back to the listing, keeping the current page and filters.
 */
export function confirmDelete(
    route: RouteDefinition<'delete'>,
    options: { message?: string; cacheTag?: string } = {},
): void {
    if (
        !window.confirm(
            options.message ??
                '¿Estás seguro de que deseas eliminar este elemento?',
        )
    ) {
        return;
    }

    router.delete(route.url, {
        preserveScroll: true,
        invalidateCacheTags: options.cacheTag,
    });
}
