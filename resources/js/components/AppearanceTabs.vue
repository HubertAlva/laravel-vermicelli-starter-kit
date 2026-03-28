<script lang="ts" setup>
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { type Appearance, useAppearance } from '@/composables/useAppearance';
import { Monitor, Moon, Sun } from 'lucide-vue-next';

interface Props {
    mode?: 'inline' | 'select';
}

const { mode = 'inline' } = defineProps<Props>();

const { appearance, updateAppearance } = useAppearance();

const tabs = [
    { value: 'light', Icon: Sun, label: 'Claro' },
    { value: 'dark', Icon: Moon, label: 'Oscuro' },
    { value: 'system', Icon: Monitor, label: 'Sistema' },
] as const;
</script>

<template>
    <div
        v-if="mode === 'inline'"
        class="inline-flex gap-1 rounded-lg bg-neutral-100 p-1 dark:bg-neutral-800"
    >
        <button
            v-for="{ value, Icon, label } in tabs"
            :key="value"
            :class="[
                'flex items-center rounded-md px-3.5 py-1.5 transition-colors',
                appearance === value
                    ? 'bg-white shadow-xs dark:bg-neutral-700 dark:text-neutral-100'
                    : 'text-neutral-500 hover:bg-neutral-200/60 hover:text-black dark:text-neutral-400 dark:hover:bg-neutral-700/60',
            ]"
            @click="updateAppearance(value)"
        >
            <component :is="Icon" class="-ml-1 h-4 w-4" />
            <span class="ml-1.5 text-sm">{{ label }}</span>
        </button>
    </div>

    <Select
        v-else-if="mode === 'select'"
        :model-value="appearance"
        @update:model-value="
            (value) => {
                if (!value) return;
                updateAppearance(value as Appearance);
            }
        "
    >
        <SelectTrigger class="w-full">
            <SelectValue placeholder="Selecciona un tema" />
        </SelectTrigger>
        <SelectContent>
            <SelectItem
                v-for="{ value, Icon, label } in tabs"
                :key="value"
                :value="value"
            >
                <div class="flex items-center gap-2">
                    <component :is="Icon" class="h-4 w-4" />
                    <span class="text-sm">{{ label }}</span>
                </div>
            </SelectItem>
        </SelectContent>
    </Select>
</template>
