<script lang="ts" setup>
import { CardHeader } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { router } from '@inertiajs/vue3';
import { debounce, pickBy } from 'lodash';
import { Search } from 'lucide-vue-next';
import { ref, watch } from 'vue';

interface Props {
    target: string;
    allowSoftDelete?: boolean;
}

const { target, allowSoftDelete = true } = defineProps<Props>();

const filters = ref({
    page: 1,
    filter: {
        search: '',
        trashed: undefined as 'only' | 'with' | undefined,
    },
});

const updateFilters = debounce(() => {
    router.get(target, pickBy(filters.value), {
        preserveState: true,
        replace: true,
    });
}, 200);

const handleTrash = () => {
    filters.value.filter.trashed =
        filters.value.filter.trashed === 'only' ? undefined : 'only';
    updateFilters();
};

watch(filters, updateFilters, { deep: true });
</script>

<template>
    <CardHeader>
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="relative w-full max-w-3xs items-center">
                <Input
                    v-model="filters.filter.search"
                    class="pl-10"
                    placeholder="Buscar..."
                    type="text"
                />
                <span
                    class="absolute inset-y-0 start-0 flex items-center justify-center px-2"
                >
                    <Search class="size-6 text-muted-foreground" />
                </span>
            </div>

            <div>
                <div v-if="allowSoftDelete" class="items-top flex space-x-2">
                    <Checkbox
                        id="only_trashed"
                        :model-value="filters.filter.trashed === 'only'"
                        @update:model-value="handleTrash"
                    />
                    <label
                        class="text-sm leading-none font-medium peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                        for="only_trashed"
                    >
                        Ver papelera
                    </label>
                </div>
            </div>
        </div>
    </CardHeader>
</template>
