import {
    FileText,
    FlaskConical,
    LayoutDashboard,
    ShieldCheck,
    Users,
} from '@lucide/vue';
import { home } from '@/routes';
import admin from '@/routes/admin';
import posts from '@/routes/admin/posts';
import roles from '@/routes/admin/roles';
import testForm from '@/routes/admin/test-form';
import users from '@/routes/admin/users';
import blog from '@/routes/blog';
import contact from '@/routes/contact';
import type { NavItem } from '@/types';

/**
 * Links shown in the public site header.
 */
export const headerNavLinks: NavItem[] = [
    {
        title: 'Inicio',
        href: home(),
        componentRoot: 'home',
    },
    {
        title: 'Blog',
        href: blog.index(),
        componentRoot: 'blog',
    },
    {
        title: 'Contacto',
        href: contact.show(),
        componentRoot: 'contact',
    },
];

/**
 * Links shown in the admin panel sidebar. Use `items` to create a
 * collapsible group, `componentRoot` to highlight the active link and
 * `permission` to show it only to users who have that permission.
 */
export const adminNavLinks: NavItem[] = [
    {
        title: 'Dashboard',
        href: admin.dashboard(),
        icon: LayoutDashboard,
        componentRoot: 'admin/dashboard',
    },
    {
        title: 'Usuarios',
        href: users.index(),
        icon: Users,
        componentRoot: 'admin/users',
        permission: 'users.viewAny',
    },
    {
        title: 'Roles y permisos',
        href: roles.index(),
        icon: ShieldCheck,
        componentRoot: 'admin/roles',
        permission: 'roles.viewAny',
    },
    {
        title: 'Contenido',
        href: posts.index(),
        icon: FileText,
        items: [
            {
                title: 'Artículos',
                href: posts.index(),
                componentRoot: 'admin/posts',
                permission: 'posts.viewAny',
            },
        ],
    },
    {
        title: 'Formulario de prueba',
        href: testForm.index(),
        icon: FlaskConical,
        componentRoot: 'admin/test-form',
    },
];
