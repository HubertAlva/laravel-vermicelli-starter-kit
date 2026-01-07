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

### 6. Start the Development Server

```bash
composer run dev
```

Your application will be available at:

- http://yourdomain.local
- http://admin.yourdomain.local

  > Vermicelli uses subdomains for the admin panel by default. Configuring a local domain early helps ensure
  authentication, sessions, and cookies work correctly across subdomains.

## What’s Included

### Authentication & Authorization

- Role and permission system ready to use
- Structured access control for administrative features

### Admin Panel

- Dedicated admin layout
- Admin panel served via subdomain (e.g. `admin.yourapp.com`)
- Example CRUD implementation for a **Post** model
- Fully functional views for create, edit, list, and delete actions

### Layouts & Components

- Basic main layout for the public-facing site
- Admin layout for internal management
- Reusable layout components
- Common form components to speed up UI development

### SEO Basics

- Meta description support
- Open Graph (OG) tags
- Simple, extendable SEO structure

### User Experience

- Toast notifications for user feedback
- Consistent UI patterns across the application

### Localization

- Spanish localization included by default

### Data & Structure

- Clean data handling approach
- Database snapshot and restore capabilities for development and testing

## Purpose

This starter kit is meant to be a **starting point**, not a full framework.  
It provides common building blocks so you can focus on building features instead of setting up the same things over and
over again.

## License

The Laravel Vermicelli Starter Kit is open-sourced software licensed under the MIT license.
