import { home } from '@/routes';
import admin from '@/routes/admin';
import posts from '@/routes/admin/posts';
import blog from '@/routes/blog';
import contact from '@/routes/contact';
import { LayoutDashboard, LucideIcon, TableOfContents } from 'lucide-vue-next';
import { ref } from 'vue';

export type NavLink = {
    name: string;
    url: string;
    componentRoot?: string;
    icon?: LucideIcon;
    isOpen?: boolean;
    items?: {
        name: string;
        url: string;
        componentRoot?: string;
    }[];
};

export const headerNavLinks = ref<NavLink[]>([
    {
        name: 'Inicio',
        url: home().url,
        componentRoot: 'home',
    },
    {
        name: 'Nosotros',
        url: home().url,
        componentRoot: 'about',
    },
    {
        name: 'Blog',
        url: blog.index().url,
        componentRoot: 'blog',
    },
    {
        name: 'Contacto',
        url: contact.index().url,
        componentRoot: 'contact',
    },
]);

export const adminNavLinks = ref<NavLink[]>([
    {
        name: 'Dashboard',
        url: admin.dashboard().url,
        icon: LayoutDashboard,
        componentRoot: 'admin/dashboard',
    },
    {
        name: 'Contenido',
        url: '#',
        icon: TableOfContents,
        items: [
            {
                name: 'Artículos',
                url: posts.index().url,
                componentRoot: 'admin/posts',
            },
        ],
    },
]);
