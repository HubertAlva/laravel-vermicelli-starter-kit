<script setup lang="ts">
import { CalendarIcon } from '@lucide/vue';
import type { DateValue, ZonedDateTime } from '@internationalized/date';
import {
    DateFormatter,
    parseAbsolute,
    Time,
    toCalendarDate,
    toCalendarDateTime,
    today,
    toZoned,
} from '@internationalized/date';
import { computed, ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
import { Input } from '@/components/ui/input';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import { cn, dateLocale, dateTimeZone } from '@/lib/utils';
import FormField from './FormField.vue';
import type { BaseInputProps } from './types';

defineOptions({ inheritAttrs: false });

/**
 * Date and time picker. The model is an ISO 8601 string (e.g. as serialized
 * by Laravel); the date and time are picked in `timeZone`.
 */
const {
    id,
    label,
    description,
    error,
    locale = dateLocale ?? 'es-PE',
    timeZone = dateTimeZone ?? 'UTC',
    placeholder = 'Selecciona una fecha',
} = defineProps<
    BaseInputProps & {
        locale?: string;
        timeZone?: string;
        placeholder?: string;
    }
>();

const model = defineModel<string | null>();

const emit = defineEmits<{ validate: [] }>();

const open = ref(false);

const zoned = computed<ZonedDateTime | null>(() => {
    try {
        return model.value ? parseAbsolute(model.value, timeZone) : null;
    } catch {
        return null;
    }
});

const pendingTime = ref('00:00');

const time = computed({
    get: () =>
        zoned.value
            ? [zoned.value.hour, zoned.value.minute]
                  .map((part) => String(part).padStart(2, '0'))
                  .join(':')
            : pendingTime.value,
    set(value: string) {
        pendingTime.value = value;

        if (date.value) {
            write(date.value, value);
        }
    },
});

const date = computed<DateValue | undefined>({
    get: () => (zoned.value ? toCalendarDate(zoned.value) : undefined),
    set(value) {
        if (!value) {
            model.value = null;
            emit('validate');

            return;
        }

        write(value, time.value);
        open.value = false;
    },
});

function write(value: DateValue, timeValue: string): void {
    const [hour = 0, minute = 0] = timeValue.split(':').map(Number);

    model.value = toZoned(
        toCalendarDateTime(value, new Time(hour, minute)),
        timeZone,
    ).toAbsoluteString();

    emit('validate');
}

const formatter = computed(
    () => new DateFormatter(locale, { dateStyle: 'long', timeZone }),
);

const formatted = computed(() =>
    zoned.value ? formatter.value.format(zoned.value.toDate()) : null,
);
</script>

<template>
    <FormField :label :for="id" :description :error>
        <div class="flex flex-wrap gap-2">
            <Popover v-model:open="open">
                <PopoverTrigger as-child>
                    <Button
                        :id
                        variant="outline"
                        :aria-invalid="!!error || undefined"
                        :class="
                            cn(
                                'w-60 justify-start text-left font-normal',
                                !date && 'text-muted-foreground',
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
                        v-model="date"
                        :default-placeholder="today(timeZone)"
                        :locale
                        layout="month-and-year"
                        initial-focus
                    />
                </PopoverContent>
            </Popover>

            <Input
                v-model="time"
                type="time"
                aria-label="Hora"
                class="w-32 bg-background [&::-webkit-calendar-picker-indicator]:hidden"
            />
        </div>
    </FormField>
</template>
