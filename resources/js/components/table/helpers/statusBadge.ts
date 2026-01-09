import { Badge, BadgeVariants } from '@/components/ui/badge';
import { h } from 'vue';

export function statusBadge(
    value: boolean,
    labels: { active: string; inactive: string } = {
        active: 'Activo',
        inactive: 'Inactivo',
    },
    variants: {
        active: BadgeVariants['variant'];
        inactive: BadgeVariants['variant'];
    } = {
        active: 'success',
        inactive: 'warn',
    },
) {
    return h(
        Badge,
        { variant: value ? variants.active : variants.inactive },
        {
            default: () => (value ? labels.active : labels.inactive),
        },
    );
}
