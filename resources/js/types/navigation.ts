import type { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from '@lucide/vue';

export type BreadcrumbItem = {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
};

export type NavItem = {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon;
    isActive?: boolean;
    /** Page component prefix used to mark the item as active (e.g. `admin/users`). */
    componentRoot?: string;
    items?: NavItem[];
    /** Hide the item unless the user has this permission. */
    permission?: string;
};
