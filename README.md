# Laravel Vermicelli Starter Kit

A clean, opinionated Laravel starter kit built to speed up development and provide a solid foundation for real-world
applications.

Built with Laravel, Inertia.js, and Vue, with a strong focus on developer experience and real-world structure.

## Requirements

- PHP 8.3+
- Composer
- Node.js & NPM
- MySQL or SQLite

## Installation Steps

### 1. Install the Laravel Installer (if not already installed)

```bash
composer global require laravel/installer
```

Make sure the Composer bin directory is in your system's PATH.

### 2. Create a New Laravel Project with Vermicelli Starter Kit

```bash
laravel new my-project --using=hubertalva/laravel-vermicelli-starter-kit
```

This command will:

- Create a new Laravel application
- Install the Vermicelli Starter Kit
- Set up all necessary dependencies

### 3. Navigate to Your Project

```bash
cd my-project
```

### 4. Configure Your Database

Edit the `.env` file and update the database connection details:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

### 5. Configure Domain and Admin Subdomain

Vermicelli is designed to work with a main domain and an admin subdomain.

Update the following values in your .env file:

```
APP_URL=http://yourdomain.local
APP_DOMAIN=yourdomain.local
SESSION_DOMAIN=.yourdomain.local
```

#### Local development (recommended)

Add domain aliases to your system hosts file:

```
127.0.0.1 yourdomain.local
127.0.0.1 admin.yourdomain.local
```

If you are using Laravel Valet, Laragon, or Docker, configure the domain according to your environment instead of
editing the hosts file manually.

#### Optional: Web Server Configuration

Depending on your local setup, you may need to ensure that both the main domain and the admin subdomain point to the
project’s `public/` directory.

Both domains should resolve to:

`/path/to/your-project/public`

If one of the domains shows a 404 or loads the wrong site, this is usually the cause.

> [Example on Ubuntu with Apache](/config-domain-example.md)

### 6. Start the Development Server

```bash
composer run dev
```

Your application will be available at:

- http://yourdomain.local
- http://admin.yourdomain.local

> Vermicelli uses subdomains for the admin panel by default. Configuring a local domain early helps ensure
> authentication, sessions, and cookies work correctly across subdomains.

## What’s Included

### Authentication & Authorization

- Laravel Fortify: login, registration, password reset, email verification, 2FA and passkeys
- Roles and permissions with `spatie/laravel-permission` (`admin` role seeded by `PermissionRoleSeeder`)
- Admins are redirected to the admin panel after login

### Admin Panel

- Served from the `admin.` subdomain and protected by the `admin.view` permission
- Collapsible sidebar (`resources/js/lib/navLinks.ts`) filtered by permission, breadcrumbs, theme switcher and user menu
- Pages under `resources/js/pages/admin/` get `AdminLayout` automatically
- Example CRUD for **users** (roles, locked users) and **posts** (Markdown content with image uploads, thumbnail, tags, drafts, trash/restore)
- A test form showcasing every form component

### Public Site

- Blog (published posts with pagination) and contact form (queued email to `MAIL_TO_ADDRESS`)

### Form & Table Components

- `resources/js/components/form`: text, textarea, password, number, phone (mask), checkbox, switch, radio, select, combobox, tags, tags listbox, date, date-time, file, image, gallery and Markdown inputs, all with label/description/error support and precognitive validation
- `resources/js/components/table/DataTable.vue`: server-side paginated, searchable and filterable tables (TanStack Table), with row prefetching

### Layouts

- `AppLayout`: public site with header, footer and toasts (default for pages)
- `UserLayout`: authenticated user area (dashboard and settings)
- `AdminLayout`: admin panel
- `AuthLayout`: authentication pages

### SEO Basics

- `Seo` component for title, meta description and Open Graph tags

### Packages

- Spatie: data, db-snapshots, medialibrary, permission, query-builder, sluggable, tags, typescript-transformer
- Mailgun mailer
- Frontend: TanStack Table, md-editor-v3, markdown-it, vue-imask, Tailwind Typography

### Localization

- Spanish localization is included by default

### Development Tools

- `php artisan snapshot:create` / `snapshot:load` for database snapshots
- `php artisan typescript:transform` generates `resources/js/types/generated.d.ts` from the Data classes
- `php artisan editor:cleanup-images` (scheduled hourly) removes unused editor uploads
- Local admin user (`admin@example.com` / `password`) seeded with `php artisan db:seed`

## Purpose

This starter kit is meant to be a **starting point**, not a full framework.  
It provides common building blocks so you can focus on building features instead of setting up the same things over and
over again.

## License

The Laravel Vermicelli Starter Kit is open-sourced software licensed under the MIT license.
