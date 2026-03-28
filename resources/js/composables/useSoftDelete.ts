import { router } from '@inertiajs/vue3';

type SoftDeleteUrls = {
    destroy: (id: number) => string;
    softDelete?: (id: number) => string;
    restore?: (id: number) => string;
};

export function useSoftDelete(
    urls: SoftDeleteUrls,
    options?: {
        confirmMessage?: string;
    },
) {
    const confirmMessage =
        options?.confirmMessage ??
        '¿Está seguro de que desea eliminar este elemento?';

    const restore = (id: number) => {
        if (!urls.restore) {
            console.warn('Restore URL is not defined.');
            return;
        }

        router.post(
            urls.restore(id),
            {},
            {
                preserveState: false,
                replace: true,
            },
        );
    };

    const remove = (id: number, isDeleted = false) => {
        let targetUrl: string;

        if (isDeleted && urls.destroy) {
            targetUrl = urls.destroy(id);
        } else if (!isDeleted && urls.softDelete) {
            targetUrl = urls.softDelete(id);
        } else {
            targetUrl = urls.destroy(id);
        }

        if (!confirm(confirmMessage)) {
            return;
        }

        router.delete(targetUrl, {
            replace: true,
        });
    };
    return {
        remove,
        restore,
    };
}
