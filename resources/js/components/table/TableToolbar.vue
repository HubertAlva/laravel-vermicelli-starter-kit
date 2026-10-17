<script setup lang="ts">
import { Search } from '@lucide/vue';
import { ref, watch } from 'vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import {
    NativeSelect,
    NativeSelectOption,
} from '@/components/ui/native-select';
import type { TableFilterDefinition } from '@/table/types';

const {
    search = '',
    filters = {},
    filterDefinitions = [],
    trashable = false,
    trashed = false,
} = defineProps<{
    search?: string;
    filters?: Record<string, string | undefined>;
    filterDefinitions?: TableFilterDefinition[];
    trashable?: boolean;
    trashed?: boolean;
}>();

const emit = defineEmits<{
    search: [value: string];
    filter: [id: string, value: string | undefined];
    'update:trashed': [value: boolean];
}>();

const searchInput = ref(search);

watch(
    () => search,
    (value) => (searchInput.value = value),
);
</script>

<template>
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div class="flex flex-wrap items-end gap-4">
            <form
                class="flex w-full max-w-xs items-center gap-2"
                role="search"
                @submit.prevent="emit('search', searchInput)"
            >
                <div class="relative w-full">
                    <Search
                        class="pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2 text-muted-foreground"
                    />
                    <Input
                        v-model="searchInput"
                        type="search"
                        placeholder="Buscar..."
                        aria-label="Buscar"
                        class="pl-9"
                    />
                </div>
                <Button
                    type="submit"
                    size="icon"
                    variant="outline"
                    aria-label="Buscar"
                >
                    <Search class="size-4" />
                </Button>
            </form>

            <label
                v-for="definition in filterDefinitions"
                :key="definition.id"
                class="grid gap-1 text-sm"
            >
                {{ definition.label }}
                <NativeSelect
                    :model-value="filters[definition.id] ?? ''"
                    class="h-9 min-w-48"
                    @update:model-value="
                        (value) =>
                            emit(
                                'filter',
                                definition.id,
                                String(value) || undefined,
                            )
                    "
                >
                    <NativeSelectOption value="">Todos</NativeSelectOption>
                    <NativeSelectOption
                        v-for="option in definition.options"
                        :key="option.value"
                        :value="option.value"
                    >
                        {{ option.label }}
                    </NativeSelectOption>
                </NativeSelect>
            </label>
        </div>

        <label
            v-if="trashable"
            class="flex items-center gap-2 text-sm font-medium"
        >
            <Checkbox
                :model-value="trashed"
                @update:model-value="
                    (value) => emit('update:trashed', value === true)
                "
            />
            Ver papelera
        </label>
    </div>
</template>
