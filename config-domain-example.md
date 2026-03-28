# Add a New Local Domain on Ubuntu (Apache + Laravel)

This guide explains how to add a **new local domain and subdomains** on Ubuntu using **Apache**.

---

## 1 Add domain and subdomain to `/etc/hosts`

Edit the hosts file:

```bash
sudo nano /etc/hosts
```

Add your domain and subdomain:

```text
127.0.0.1 my-project.local
127.0.0.1 admin.my-project.local
```

> You can add more subdomains later without touching Apache if you use a wildcard vhost.

Save and exit.

---

## 2 Create the Apache VirtualHost config

Create a new config file in `sites-available`:

```bash
sudo nano /etc/apache2/sites-available/my-project.local.conf
```

### Example (Laravel-ready, with wildcard subdomains)

```apache
Define NAME "my-project"
Define ROOT "/var/www/${NAME}/public"
Define SITE "${NAME}.local"

<VirtualHost *:80>
    ServerName ${SITE}
    ServerAlias *.${SITE}
    DocumentRoot "${ROOT}"

    <Directory "${ROOT}">
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/${NAME}_error.log
    CustomLog ${APACHE_LOG_DIR}/${NAME}_access.log combined
</VirtualHost>
```

### Notes

- `DocumentRoot` **must point to `public/`** for Laravel
- `ServerAlias *.domain.local` enables all subdomains
- Laravel handles routing internally

Save and exit.

---

## 3 Enable the site in Apache

Enable the new VirtualHost:

```bash
sudo a2ensite my-project.local.conf
```

Reload Apache:

```bash
sudo systemctl reload apache2
```

(Optional) Check enabled sites:

```bash
apachectl -S
```

---

Here is a clean, updated version of your README section that reflects the improved approach and promotes the global utility.

---

## 4. Set correct permissions (IMPORTANT)

Apache runs as `www-data`, so your project must allow the web server to write to specific directories.

Instead of applying broad permissions to the entire project, use a reusable utility that applies **secure, minimal permissions**.

---

### Recommended: Global Laravel permissions utility

Create a global command to fix permissions in any Laravel project.

#### 1. Create the script

```bash
sudo nano /usr/local/bin/laravel-perm
```

Paste:

```bash
#!/bin/bash

OWNER=${SUDO_USER:-$USER}

echo "Applying permissions in $(pwd)..."

# Ensure it's a Laravel project
if [ ! -f "artisan" ]; then
    echo "Not a Laravel project (artisan not found). Aborting."
    exit 1
fi

# 1. Ownership (you + web server group)
sudo chown -R $OWNER:www-data .

# 2. Make everything readable/writable
sudo chmod -R 777 .

# 3. Ensure executables still work (Node + Composer binaries)
if [ -d "node_modules/.bin" ]; then
    chmod -R 755 node_modules/.bin
fi

if [ -d "vendor/bin" ]; then
    chmod -R 755 vendor/bin
fi

# 4. Ensure Laravel writable dirs (redundant but explicit)
mkdir -p storage bootstrap/cache
chmod -R 777 storage bootstrap/cache

# 5. .env (fully editable)
if [ -f ".env" ]; then
    chmod 666 .env
fi

echo "Done."
```

---

#### 2. Make it executable

```bash
sudo chmod +x /usr/local/bin/laravel-perm
```

---

#### 3. Usage

From your project root:

```bash
laravel-perm
```

---

### Why this approach?

* Removes permissions as a source of bugs entirely
* Ensures **everything is writable**, so nothing breaks during development
* Explicitly restores executable permissions for:

    * `node_modules/.bin`
    * `vendor/bin`
* Prevents common errors like:

```
file_put_contents(...): Permission denied
sh: 1: concurrently: Permission denied
```

---

### Note

This setup is intentionally **fully permissive (`777`)** and is designed **only for local development**.

Do **not** use this approach in production environments.

---

## 5 Verify in browser

Open:

- http://my-project.local
- http://admin.my-project.local

If something fails, check logs:

```bash
sudo tail -f /var/log/apache2/my-project_error.log
```
