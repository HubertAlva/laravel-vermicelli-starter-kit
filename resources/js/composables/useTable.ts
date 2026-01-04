import { Filters } from '@/types/adminTable';
import { router } from '@inertiajs/vue3';
import { debounce, pickBy } from 'lodash';
import { ref } from 'vue';

export function useTableFilters(url: string, initial: Filters) {
    const filters = ref<Filters>(initial);

    const refresh = debounce(() => {
        const normalized = {
            ...filters.value,
            filter: {
                ...filters.value.filter,
                search: filters.value.filter.search?.trim() || undefined,
            },
        };

        router.get(url, pickBy(normalized), {
            preserveState: true,
            replace: true,
        });
    }, 200);

    return {
        filters,
        refresh,
    };
}
