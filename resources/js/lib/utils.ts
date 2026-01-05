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

export function truncateText(text: string, maxLength: number) {
    if (text.length <= maxLength) {
        return text;
    }
    return text.substring(0, maxLength) + '...';
}

export function formatDate(date: string | undefined, format = 'long') {
    if (!date) return '';

    if (format === 'long') {
        return new Date(date).toLocaleDateString('es-PE', {
            day: 'numeric',
            month: 'long',
            year: 'numeric',
        });
    } else if (format === 'short') {
        return new Date(date).toLocaleDateString('es-PE', {
            day: 'numeric',
            month: 'short',
            year: 'numeric',
        });
    }
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
