import type { InertiaLinkProps } from '@inertiajs/vue3';
import { clsx } from 'clsx';
import type { ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function toUrl(href: NonNullable<InertiaLinkProps['href']>) {
    return typeof href === 'string' ? href : href?.url;
}

export function truncateText(text: string, maxLength = 100): string {
    if (text.length <= maxLength) {
        return text;
    }

    return `${text.substring(0, maxLength)}...`;
}

export const dateLocale: string | undefined = import.meta.env.VITE_DATE_LOCALE;
export const dateTimeZone: string | undefined = import.meta.env
    .VITE_DATE_TIMEZONE;

export function formatDate(
    date: string | null | undefined,
    options?: Intl.DateTimeFormatOptions,
    locale = dateLocale,
    timeZone = dateTimeZone,
): string {
    if (!date) {
        return '';
    }

    return new Date(date).toLocaleDateString(locale, {
        timeZone,
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        ...options,
    });
}

export function formatDateTime(
    date: string | null | undefined,
    options?: Intl.DateTimeFormatOptions,
    locale = dateLocale,
    timeZone = dateTimeZone,
): string {
    if (!date) {
        return '';
    }

    return new Date(date).toLocaleString(locale, {
        timeZone,
        dateStyle: 'short',
        timeStyle: 'short',
        ...options,
    });
}

export function formatSize(bytes: number, decimals = 2): string {
    if (bytes === 0) {
        return '0 Bytes';
    }

    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.min(
        Math.floor(Math.log(bytes) / Math.log(k)),
        sizes.length - 1,
    );

    return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`;
}
