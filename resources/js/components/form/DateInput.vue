<script setup lang="ts">
import { CalendarIcon } from '@lucide/vue';
import type { DateValue } from '@internationalized/date';
import {
    DateFormatter,
    getLocalTimeZone,
    parseDate,
    today,
} from '@internationalized/date';
import { computed, ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import { cn, dateLocale } from '@/lib/utils';
import FormField from './FormField.vue';
import type { BaseInputProps } from './types';

defineOptions({ inheritAttrs: false });

/**
 * Calendar date picker. The model is a plain `YYYY-MM-DD` string, so no
 * timezone conversion ever shifts the selected day.
 */
const {
    id,
    label,
    description,
    error,
    locale = dateLocale ?? 'es-PE',
    placeholder = 'Selecciona una fecha',
} = defineProps<BaseInputProps & { locale?: string; placeholder?: string }>();

const model = defineModel<string | null>();

const emit = defineEmits<{ validate: [] }>();

const open = ref(false);

const formatter = computed(
    () => new DateFormatter(locale, { dateStyle: 'long' }),
);

const value = computed<DateValue | undefined>({
    get() {
        try {
            return model.value
                ? parseDate(model.value.slice(0, 10))
                : undefined;
        } catch {
            return undefined;
        }
    },
    set(date) {
        model.value = date ? date.toString() : null;
        open.value = false;
        emit('validate');
    },
});

const formatted = computed(() =>
    value.value
        ? formatter.value.format(value.value.toDate(getLocalTimeZone()))
        : null,
);
</script>

<template>
    <FormField :label :for="id" :description :error>
        <Popover v-model:open="open">
            <PopoverTrigger as-child>
                <Button
                    :id
                    variant="outline"
                    :aria-invalid="!!error || undefined"
                    :class="
                        cn(
                            'w-60 justify-start text-left font-normal',
                            !value && 'text-muted-foreground',
                        )
                    "
                    v-bind="$attrs"
                >
                    <CalendarIcon class="mr-2 size-4" />
                    {{ formatted ?? placeholder }}
                </Button>
            </PopoverTrigger>

            <PopoverContent align="start" class="w-auto p-0">
                <Calendar
                    v-model="value"
                    :default-placeholder="today(getLocalTimeZone())"
                    :locale
                    layout="month-and-year"
                    initial-focus
                />
            </PopoverContent>
        </Popover>
    </FormField>
</template>
