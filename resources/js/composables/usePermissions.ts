import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

export type UsePermissionsReturn = {
    can: (permission: string) => boolean;
    canAny: (permissions: string[]) => boolean;
};

export function usePermissions(): UsePermissionsReturn {
    const page = usePage();

    const permissions = computed(
        () => new Set(page.props.auth.permissions ?? []),
    );

    function can(permission: string): boolean {
        return permissions.value.has(permission);
    }

    function canAny(permissions: string[]): boolean {
        return permissions.some(can);
    }

    return { can, canAny };
}
