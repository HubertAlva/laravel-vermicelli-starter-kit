import { Badge } from '@/components/ui/badge';
import { Role } from '@/types';
import { h } from 'vue';

type BadgeVariant = NonNullable<
    InstanceType<typeof Badge>['$props']['variant']
>;

export function roleBadge(role?: Role) {
    const variants: Partial<Record<Role, BadgeVariant>> = {
        admin: 'default',
    };

    const labels: Partial<Record<Role, string>> = {
        admin: 'Admin',
    };

    const variant: BadgeVariant =
        role && variants[role] ? variants[role]! : 'secondary';

    const label = role && labels[role] ? labels[role]! : 'Sin rol';

    return h(
        Badge,
        { variant },
        {
            default: () => label,
        },
    );
}
