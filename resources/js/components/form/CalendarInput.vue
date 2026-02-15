<script lang="ts" setup>
import type { DateValue } from '@internationalized/date';
import {
    DateFormatter,
    getLocalTimeZone,
    parseAbsolute,
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
import { computed } from 'vue';

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

const defaultPlaceholder = today(getLocalTimeZone());

const dateValue = computed<DateValue | undefined>({
    get() {
        return model.value
            ? parseAbsolute(model.value, getLocalTimeZone())
            : undefined;
    },
    set(value) {
        model.value = value
            ? value.toDate(getLocalTimeZone()).toISOString()
            : undefined;
    },
});

const df = new DateFormatter('es-PE', {
    dateStyle: 'long',
});
</script>

<template>
    <Field :data-invalid="!!props.error">
        <FieldLabel v-if="props.label">
            {{ props.label }}
        </FieldLabel>

        <Popover v-slot="{ close }">
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
                    @update:model-value="close"
                />
            </PopoverContent>
        </Popover>

        <FieldDescription v-if="props.description">
            {{ props.description }}
        </FieldDescription>

        <FieldError v-if="props.error" :errors="[props.error]" />
    </Field>
</template>
