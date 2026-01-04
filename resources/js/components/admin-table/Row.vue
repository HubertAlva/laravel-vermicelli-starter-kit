<script generic="T extends AdminRow" lang="ts" setup>
import { Button } from '@/components/ui/button';
import { TableCell, TableRow } from '@/components/ui/table';
import Tooltip from '@/plugins/Tooltip.vue';
import type { AdminRow, Column } from '@/types/adminTable';
import { router } from '@inertiajs/vue3';
import { Trash } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import CellContent from './partials/CellContent.vue';

interface Props {
    // filters: App.Data.FiltersData;
    item: T;
    columns: Column<T>[];
    target: string;
    allowSoftDelete?: boolean;
    allowActions?: boolean;
}

const {
    // filters,
    item,
    columns,
    target,
    allowSoftDelete = true,
    allowActions = true,
} = defineProps<Props>();

// const localFilters = ref({
//     search: filters.search,
//     per_page: filters.per_page || 10,
//     page: filters.page || 1,
//     sort_by: filters.sort_by || 'id',
//     sort_dir: filters.sort_dir || 'desc',
//     only_trashed: filters.only_trashed || false,
// });

// const updateFilters = debounce(() => {
//     router.get(
//         route('admin.' + target + '.index'),
//         pickBy(localFilters.value),
//         {
//             preserveState: true,
//             replace: true,
//         },
//     );
// }, 200);

const handleDelete = (row: T) => {
    const routeName = ref(
        row.deleted_at !== null
            ? 'admin.' + target + '.destroy'
            : 'admin.' + target + '.soft-delete',
    );

    console.log(routeName.value);

    if (!allowSoftDelete) {
        routeName.value = 'admin.' + target + '.destroy';
    }

    if (!confirm('¿Está seguro de que desea eliminar este elemento?')) {
        return;
    }

    router.delete(route(routeName.value, { id: row.id }), {
        preserveState: true,
        replace: true,
        // onSuccess: () => {
        //     updateFilters();
        // },
    });
};

const deleteTooltip = computed(() => {
    const label = ref('');

    // if (allowSoftDelete) {
    //     label.value = localFilters.value.only_trashed
    //         ? 'Eliminar'
    //         : 'Mover a papelera';
    // } else {
    //     label.value = 'Eliminar permanentemente';
    // }

    return label.value;
});

// watch(
//     () => filters,
//     () => {
//         updateFilters();
//     },
//     { deep: true },
// );
</script>

<template>
    <TableRow class="cursor-pointer">
        <TableCell
            v-for="(column, cellIndex) in columns"
            :key="column.field"
            :class="[
                column.type === 'image' || column.type === 'images'
                    ? 'w-14'
                    : '',
                cellIndex === columns.length - 1 ? 'text-right' : '',
            ]"
        >
            <CellContent :column="column" :row="item" />
        </TableCell>

        <TableCell v-if="allowActions" @click.stop>
            <div class="flex items-center justify-end gap-2">
                <Tooltip :text="deleteTooltip" side="left">
                    <Button
                        class="cursor-pointer"
                        size="icon"
                        type="button"
                        variant="destructive"
                        @click="handleDelete(item)"
                    >
                        <Trash />
                    </Button>
                </Tooltip>
            </div>
        </TableCell>
    </TableRow>
</template>
