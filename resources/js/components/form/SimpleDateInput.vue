<script lang="ts" setup>
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
import { cn, dateLocale, dateTimeZone } from '@/lib/utils';
import type { DateValue } from '@internationalized/date';
import { DateFormatter, parseDate, today } from '@internationalized/date';
import { CalendarIcon } from 'lucide-vue-next';
import { computed, ref } from 'vue';

interface Props {
    label?: string;
    description?: string;
    error?: string;
    locale?: string;
    timeZone?: string;
}

const props = withDefaults(defineProps<Props>(), {
    locale: dateLocale,
    timeZone: dateTimeZone,
});

defineOptions({
    inheritAttrs: false,
});

// Two-way binding. This will now read and write plain strings: "YYYY-MM-DD"
const model = defineModel<string | null>();
const open = ref(false);

const emit = defineEmits<{
    (e: 'validate'): void;
}>();

// Establishes a local calendar placeholder instance
const defaultPlaceholder = today(props.timeZone);

function parseModel(value: string) {
    try {
        // Parses native clean "YYYY-MM-DD" string format safely
        return parseDate(value);
    } catch {
        return null;
    }
}

const dateValue = computed<DateValue | undefined>({
    get() {
        if (!model.value) return undefined;
        const parsed = parseModel(model.value);
        return parsed ?? undefined;
    },
    set(value) {
        if (!value) {
            model.value = null;
            return;
        }
        // value.toString() outputs clean format: "YYYY-MM-DD"
        model.value = value.toString();
    },
});

const df = new DateFormatter(props.locale, {
    dateStyle: 'long',
    timeZone: props.timeZone,
});

const formattedDate = computed(() => {
    if (!model.value) return null;
    const parsed = parseModel(model.value);
    if (!parsed) return null;
    return df.format(parsed.toDate(props.timeZone));
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
                    {{ formattedDate ?? 'Selecciona una fecha' }}
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
