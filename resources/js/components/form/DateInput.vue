<script lang="ts" setup>
import type { DateValue } from '@internationalized/date';
import {
    DateFormatter,
    getLocalTimeZone,
    parseAbsolute,
    parseDate,
    today,
} from '@internationalized/date';

import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
import {
    Field,
    FieldDescription,
    FieldError,
    FieldLabel,
} from '@/components/ui/field';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import { cn } from '@/lib/utils';
import { CalendarIcon } from 'lucide-vue-next';
import { computed, ref } from 'vue';

interface Props {
    label?: string;
    description?: string;
    error?: string;
}

const props = defineProps<Props>();

defineOptions({
    inheritAttrs: false,
});

const model = defineModel<string | undefined | null>();

const open = ref(false);

const emit = defineEmits<{
    (e: 'validate'): void;
}>();

const defaultPlaceholder = today(getLocalTimeZone());

const dateValue = computed<DateValue | undefined>({
    get() {
        if (!model.value) return undefined;

        // DATE ONLY (like 2026-03-05)
        if (model.value.length === 10) {
            return parseDate(model.value);
        }

        // ISO datetime (like 2026-03-05T19:12:36-05:00)
        return parseAbsolute(model.value, getLocalTimeZone());
    },

    set(value) {
        if (!value) {
            model.value = undefined;
            return;
        }

        const date = value.toDate(getLocalTimeZone());

        model.value = date.toISOString().slice(0, 10);
    },
});

const df = new DateFormatter('es-PE', {
    dateStyle: 'long',
});

function handleModelUpdate() {
    open.value = false;
    emit('validate');
}
</script>

<template>
    <Field :data-invalid="!!props.error">
        <FieldLabel v-if="props.label">
            {{ props.label }}
        </FieldLabel>

        <Popover v-model:open="open">
            <PopoverTrigger as-child>
                <Button
                    :class="
                        cn(
                            'w-60 justify-start text-left font-normal',
                            !dateValue && 'text-muted-foreground',
                        )
                    "
                    v-bind="$attrs"
                    variant="outline"
                >
                    <CalendarIcon class="mr-2 h-4 w-4" />
                    {{
                        dateValue
                            ? df.format(dateValue.toDate(getLocalTimeZone()))
                            : 'Selecciona una fecha'
                    }}
                </Button>
            </PopoverTrigger>

            <PopoverContent align="start" class="w-auto p-0">
                <Calendar
                    v-model="dateValue"
                    :default-placeholder="defaultPlaceholder"
                    initial-focus
                    layout="month-and-year"
                    locale="es-PE"
                    @update:modelValue="handleModelUpdate"
                />
            </PopoverContent>
        </Popover>

        <FieldDescription v-if="props.description">
            {{ props.description }}
        </FieldDescription>

        <FieldError v-if="props.error" :errors="[props.error]" />
    </Field>
</template>
