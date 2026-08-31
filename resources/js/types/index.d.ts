import { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

export interface Auth {
    user: User;
    isAdmin: boolean;
    can: {
        [key: string]: boolean;
    };
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon;
    isActive?: boolean;
    componentRoot?: string;
}

export type AppPageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    name: string;
    isLocal: boolean;
    quote: { message: string; author: string };
    auth: Auth;
    sidebarOpen: boolean;
};

export type Role = 'admin';

export interface User {
    id: number;
    name: string;
    email: string;
    role?: Role;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface Toast {
    type: 'success' | 'info' | 'warning' | 'error';
    title?: string;
    message: string;
    id?: number;
}

export type BreadcrumbItemType = BreadcrumbItem;
