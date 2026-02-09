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

## 4 Set correct permissions (IMPORTANT)

Apache runs as `www-data`. Your user must share access.

### Recommended permissions (project-level)

```bash
sudo chown -R $USER:www-data /var/www/my-project
sudo chmod -R 775 /var/www/my-project
```

This prevents errors like:

```
file_put_contents(... Permission denied)
```

---

## 5 Verify in browser

Open:

- http://my-project.local
- http://admin.my-project.local

If something fails, check logs:

```bash
sudo tail -f /var/log/apache2/my-project_error.log
```
