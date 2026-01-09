import { router } from '@inertiajs/vue3';

type DeleteRoutes = {
    destroyUrl: string;
    softDeleteUrl?: string;
};

export function useDeleteAction<
    T extends { id: number; deleted_at?: string | null },
>(routes: DeleteRoutes) {
    const deleteModel = (model: T) => {
        const url =
            model.deleted_at && routes.destroyUrl
                ? routes.destroyUrl
                : routes.softDeleteUrl
                  ? routes.softDeleteUrl
                  : routes.destroyUrl;

        if (!confirm('¿Está seguro de que desea eliminar este elemento?')) {
            return;
        }

        router.delete(url, {
            replace: true,
        });
    };

    return { deleteModel };
}
