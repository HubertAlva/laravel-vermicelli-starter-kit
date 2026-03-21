export function formatDate(
    date: string,
    options?: Intl.DateTimeFormatOptions,
    locale = import.meta.env.VITE_DATE_LOCALE,
    timeZone = import.meta.env.VITE_DATE_TIMEZONE,
): string {
    return new Date(date).toLocaleDateString(locale, {
        timeZone,
        ...options,
    });
}
