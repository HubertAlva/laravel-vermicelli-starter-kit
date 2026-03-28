import { dateLocale, dateTimeZone } from '@/lib/utils';

export function formatDate(
    date: string,
    options?: Intl.DateTimeFormatOptions,
    locale = dateLocale,
    timeZone = dateTimeZone,
): string {
    return new Date(date).toLocaleDateString(locale, {
        timeZone,
        ...options,
    });
}
