import { router, usePage } from '@inertiajs/vue3';

type DeleteRoutes = {
    destroyUrl: string;
    softDeleteUrl?: string;
};

const page = usePage();

export function useDeleteAction<
    T extends { id: number; deleted_at?: string | null },
>(routes: DeleteRoutes) {
    const deleteModel = (model: T, modelName: string) => {
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
            data: {
                currentPage: page.props[modelName].meta.current_page,
                trashed: model.deleted_at ? 'only' : null,
            },
        });
    };

    return { deleteModel };
}
