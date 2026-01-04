<script generic="T extends AdminRow" lang="ts" setup>
import { Button } from '@/components/ui/button';
import { TableCell, TableRow } from '@/components/ui/table';
import Tooltip from '@/plugins/Tooltip.vue';
import { AdminRow, RowProps } from '@/types/adminTable';
import { router } from '@inertiajs/vue3';
import { Trash } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import CellContent from './partials/CellContent.vue';

const {
    filters,
    item,
    columns,
    url,
    allowSoftDelete = true,
    allowActions = true,
} = defineProps<RowProps<T>>();

const emit = defineEmits<{
    (e: 'refresh'): void;
}>();

const handleDelete = (row: T) => {
    const targetUrl = ref(
        row.deleted_at !== null
            ? `${url}/ ${row.id}`
            : `${url}/${row.id}/soft-delete`,
    );

    if (!allowSoftDelete) {
        targetUrl.value = `${url}/ ${row.id}`;
    }

    if (!confirm('¿Está seguro de que desea eliminar este elemento?')) {
        return;
    }

    router.delete(targetUrl.value, {
        preserveState: true,
        replace: true,
        onSuccess: () => {
            emit('refresh');
        },
    });
};

const deleteTooltip = computed(() => {
    const label = ref('');

    if (allowSoftDelete) {
        label.value =
            filters.filter.trashed === 'only' ? 'Eliminar' : 'Mover a papelera';
    } else {
        label.value = 'Eliminar permanentemente';
    }

    return label.value;
});
</script>

<template>
    <TableRow>
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
