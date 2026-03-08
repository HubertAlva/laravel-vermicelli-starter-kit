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
import { cn } from '@/lib/utils';
import type { DateValue } from '@internationalized/date';
import {
    DateFormatter,
    getLocalTimeZone,
    parseDate,
    today,
} from '@internationalized/date';
import { CalendarIcon } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

interface Props {
    label?: string;
    description?: string;
    error?: string;
}

const props = defineProps<Props>();

defineOptions({
    inheritAttrs: false,
});

const model = defineModel<string | undefined>();

const emit = defineEmits<{
    (e: 'validate'): void;
}>();

const open = ref(false);

const defaultPlaceholder = today(getLocalTimeZone());

const time = ref('00:00:00');

const dateValue = computed<DateValue | undefined>({
    get() {
        if (!model.value) return undefined;

        const date = new Date(model.value);

        return parseDate(date.toISOString().slice(0, 10));
    },

    set(value) {
        if (!value) {
            model.value = undefined;
            return;
        }

        if (!isValidTime(time.value)) return;

        const date = value.toDate(getLocalTimeZone());

        const [h, m, s] = time.value.split(':');

        date.setHours(Number(h), Number(m), Number(s));

        model.value = date.toISOString();
    },
});

watch(
    model,
    (value) => {
        if (!value) return;

        const d = new Date(value);

        const hh = String(d.getHours()).padStart(2, '0');
        const mm = String(d.getMinutes()).padStart(2, '0');
        const ss = String(d.getSeconds()).padStart(2, '0');

        time.value = `${hh}:${mm}:${ss}`;
    },
    { immediate: true },
);

watch(time, () => {
    if (!dateValue.value) return;

    if (!isValidTime(time.value)) return;

    const date = dateValue.value.toDate(getLocalTimeZone());

    const [h, m, s] = time.value.split(':');

    date.setHours(Number(h), Number(m), Number(s));

    model.value = date.toISOString();
});

const df = new DateFormatter('es-PE', {
    dateStyle: 'long',
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
                        {{
                            dateValue
                                ? df.format(
                                      dateValue.toDate(getLocalTimeZone()),
                                  )
                                : 'Selecciona una fecha'
                        }}
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
