export type OptionValue = string | number;

/**
 * How to read a label or value from an option: a key of the option object
 * or a function. Without one, `name`/`label` and `id`/`value` are used.
 */
export type OptionAccessor<T, R> = keyof T | ((option: T) => R);

export type NormalizedOption = {
    value: OptionValue;
    label: string;
};

function read<T, R>(option: T, accessor: OptionAccessor<T, R>): R {
    return typeof accessor === 'function'
        ? accessor(option)
        : (option[accessor] as R);
}

export function getOptionLabel<T>(
    option: T,
    accessor?: OptionAccessor<T, string>,
): string {
    if (accessor) {
        return String(read(option, accessor));
    }

    if (typeof option === 'object' && option !== null) {
        const { name, label } = option as { name?: string; label?: string };

        return name ?? label ?? '';
    }

    return String(option);
}

export function getOptionValue<T>(
    option: T,
    accessor?: OptionAccessor<T, OptionValue>,
): OptionValue {
    if (accessor) {
        return read(option, accessor);
    }

    if (typeof option === 'object' && option !== null) {
        const record = option as Record<string, unknown>;

        return (record.id ?? record.value) as OptionValue;
    }

    return option as OptionValue;
}

export function normalizeOptions<T>(
    options: T[],
    labelAccessor?: OptionAccessor<T, string>,
    valueAccessor?: OptionAccessor<T, OptionValue>,
): NormalizedOption[] {
    return options.map((option) => ({
        value: getOptionValue(option, valueAccessor),
        label: getOptionLabel(option, labelAccessor),
    }));
}
