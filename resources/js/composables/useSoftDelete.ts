import { router } from '@inertiajs/vue3';

type SoftDeleteUrls = {
    restore: (id: number) => string;
    destroy: (id: number) => string;
    softDelete: (id: number) => string;
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
        router.post(
            urls.restore(id),
            {},
            {
                preserveState: false,
                replace: true,
            },
        );
    };

    const remove = (id: number, isDeleted: boolean) => {
        const targetUrl = isDeleted ? urls.destroy(id) : urls.softDelete(id);

        if (!confirm(confirmMessage)) {
            return;
        }

        router.delete(targetUrl, {
            replace: true,
        });
    };

    return {
        restore,
        remove,
    };
}
