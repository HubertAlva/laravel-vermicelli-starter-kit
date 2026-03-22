import { type FormDataConvertible } from '@inertiajs/core';
import { InertiaLinkProps, InertiaPrecognitiveForm } from '@inertiajs/vue3';
import { useDebounceFn } from '@vueuse/core';
import { type ClassValue, clsx } from 'clsx';
import markdownit from 'markdown-it';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function urlIsActive(
    urlToCheck: NonNullable<InertiaLinkProps['href']>,
    currentUrl: string,
) {
    return toUrl(urlToCheck) === currentUrl;
}

export function toUrl(href: NonNullable<InertiaLinkProps['href']>) {
    return typeof href === 'string' ? href : href?.url;
}

export function truncateText(text: string, maxLength: number = 100) {
    if (text.length <= maxLength) {
        return text;
    }
    return text.substring(0, maxLength) + '...';
}

export const dateLocale: string = import.meta.env.VITE_DATE_LOCALE;
export const dateTimeZone: string = import.meta.env.VITE_DATE_TIMEZONE;

export function formatDate(
    date: string | undefined,
    options?: Intl.DateTimeFormatOptions,
    locale = dateLocale,
    timeZone = dateTimeZone,
) {
    if (!date) return '';

    const defaultOptions: Intl.DateTimeFormatOptions = {
        timeZone,
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    };

    return new Date(date).toLocaleDateString(locale, {
        ...defaultOptions,
        ...options,
    });
}

export function formatDateTime(
    date: string | undefined,
    options?: Intl.DateTimeFormatOptions,
    locale = dateLocale,
    timeZone = dateTimeZone,
): string | undefined {
    if (!date) return '';

    const defaultOptions: Intl.DateTimeFormatOptions = {
        timeZone,
        dateStyle: 'short',
        timeStyle: 'short',
    };

    return new Date(date).toLocaleString(locale, {
        ...defaultOptions,
        ...options,
    });
}

export function renderMarkdown(content: string) {
    const md = markdownit({
        html: true,
    });

    return md.render(content);
}

export function removeSpaces(value: FormDataConvertible) {
    if (value == null) return value;
    return String(value).replace(/\s/g, '');
}

export function validate(form: InertiaPrecognitiveForm<any>, delay = 200) {
    return useDebounceFn((field: keyof typeof form.errors) => {
        form.validate(field);
    }, delay);
}

export function formatSize(bytes: number, decimals = 2) {
    if (bytes === 0) return '0 Bytes';

    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

    const i = Math.floor(Math.log(bytes) / Math.log(k));

    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}
