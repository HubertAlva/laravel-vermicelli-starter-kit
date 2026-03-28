<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
import {
    Field,
    FieldDescription,
    FieldError,
    FieldLabel,
} from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import { cn, dateLocale, dateTimeZone } from '@/lib/utils';
import type { DateValue } from '@internationalized/date';
import {
    DateFormatter,
    parseAbsolute,
    parseZonedDateTime,
    Time,
    toCalendarDate,
    toCalendarDateTime,
    today,
    toZoned,
} from '@internationalized/date';
import { CalendarIcon } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

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

const model = defineModel<string | null>();

const emit = defineEmits<{
    (e: 'validate'): void;
}>();

const open = ref(false);

const defaultPlaceholder = today(props.timeZone);

const time = ref('00:00:00');

function parseModel(value: string) {
    try {
        // Case 1: UTC string (Z)
        return parseAbsolute(value, props.timeZone);
    } catch {
        try {
            // Case 2: Laravel serialized timezone string
            return parseZonedDateTime(value);
        } catch {
            return null;
        }
    }
}

function buildAbsolute(value: DateValue, timeStr: string) {
    if (!isValidTime(timeStr)) return null;

    const [h, m, s] = timeStr.split(':').map(Number);

    const dateTime = toCalendarDateTime(value, new Time(h, m, s));

    return toZoned(dateTime, props.timeZone).toAbsoluteString();
}

const dateValue = computed<DateValue | undefined>({
    get() {
        if (!model.value) return undefined;

        const parsed = parseModel(model.value);
        if (!parsed) return undefined;

        return toCalendarDate(parsed);
    },

    set(value) {
        if (!value) {
            model.value = null;
            return;
        }

        const result = buildAbsolute(value, time.value);
        if (result) model.value = result;
    },
});

watch(
    model,
    (value) => {
        if (!value) return;

        const zdt = parseModel(value);
        if (!zdt) return;

        time.value = [zdt.hour, zdt.minute, zdt.second]
            .map((v) => String(v).padStart(2, '0'))
            .join(':');
    },
    { immediate: true },
);

watch(time, () => {
    if (!dateValue.value) return;

    const result = buildAbsolute(dateValue.value, time.value);
    if (result) model.value = result;
});

const df = new DateFormatter(props.locale, {
    dateStyle: 'long',
    timeZone: props.timeZone,
});

const formattedDate = computed(() => {
    if (!model.value) return null;

    const parsed = parseModel(model.value);
    if (!parsed) return null;

    return df.format(parsed.toDate());
});

function handleModelUpdate() {
    open.value = false;
    emit('validate');
}

function isValidTime(value: string) {
    return /^(\d{2}):(\d{2}):(\d{2})$/.test(value);
}
</script>

<template>
    <Field :data-invalid="!!props.error">
        <FieldLabel v-if="props.label">
            {{ props.label }}
        </FieldLabel>

        <div class="flex flex-wrap gap-2">
            <Popover v-model:open="open">
                <PopoverTrigger as-child>
                    <Button
                        variant="outline"
                        v-bind="$attrs"
                        :class="
                            cn(
                                'w-60 justify-start text-left font-normal',
                                !dateValue && 'text-muted-foreground',
                            )
                        "
                    >
                        <CalendarIcon class="mr-2 h-4 w-4" />
                        {{ formattedDate ?? 'Selecciona una fecha' }}
                    </Button>
                </PopoverTrigger>

                <PopoverContent align="start" class="w-auto p-0">
                    <Calendar
                        v-model="dateValue"
                        :default-placeholder="defaultPlaceholder"
                        layout="month-and-year"
                        locale="es-PE"
                        initial-focus
                        @update:modelValue="handleModelUpdate"
                    />
                </PopoverContent>
            </Popover>

            <Input
                class="w-32 appearance-none bg-background [&::-webkit-calendar-picker-indicator]:hidden [&::-webkit-calendar-picker-indicator]:appearance-none"
                type="time"
                step="1"
                v-model="time"
                @update:modelValue="emit('validate')"
            />
        </div>

        <FieldDescription v-if="props.description">
            {{ props.description }}
        </FieldDescription>

        <FieldError v-if="props.error" :errors="[props.error]" />
    </Field>
</template>
