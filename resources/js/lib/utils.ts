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

export function formatDate(date: string | undefined) {
    if (!date) return '';
    return new Date(date).toLocaleDateString('es-ES', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
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

export function validate(form: InertiaPrecognitiveForm<any>, delay = 300) {
    return useDebounceFn((field: keyof typeof form.errors) => {
        form.validate(field);
    }, delay);
}
