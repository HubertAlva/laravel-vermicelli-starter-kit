import { h } from 'vue';
import type { VNode } from 'vue';
import { Badge } from '@/components/ui/badge';
import type { BadgeVariants } from '@/components/ui/badge';
import { formatDate } from '@/lib/utils';

type BadgeVariant = BadgeVariants['variant'];

export function headerCell(
    label: string,
    align: 'start' | 'end' = 'start',
): VNode {
    return h('div', { class: `text-${align}` }, label);
}

export function textCell(
    value: string | number | null | undefined,
    className = 'text-start',
): VNode {
    return h('div', { class: className }, value ?? '');
}

export function dateCell(
    value: string | null,
    align: 'start' | 'end' = 'end',
): VNode {
    return h(
        'div',
        { class: `text-${align} whitespace-nowrap` },
        formatDate(value),
    );
}

export function statusBadge(
    active: boolean,
    labels = { active: 'Activo', inactive: 'Inactivo' },
    variants: { active: BadgeVariant; inactive: BadgeVariant } = {
        active: 'success',
        inactive: 'warn',
    },
): VNode {
    return h(
        Badge,
        { variant: active ? variants.active : variants.inactive },
        () => (active ? labels.active : labels.inactive),
    );
}

export function roleBadge(role: string | null): VNode {
    return h(Badge, { variant: role ? 'default' : 'secondary' }, () =>
        role ? role.charAt(0).toUpperCase() + role.slice(1) : 'Sin rol',
    );
}
