# Wiseman BD

## Portable Setup

No Composer installation or Artisan command is required because `vendor/` is included in this source package.

1. Copy the complete source folder to the new local server or cPanel account.
2. Create a MySQL/MariaDB database and import `ecommerce9.sql` using phpMyAdmin or MySQL.
3. Edit `.env` and set only the server-specific values:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL="https://your-domain.com"
DB_HOST="localhost"
DB_PORT="3306"
DB_DATABASE="your_database"
DB_USERNAME="your_database_user"
DB_PASSWORD="your_database_password"
SESSION_DRIVER=file
SESSION_DOMAIN=your-domain.com
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
FORCE_HTTPS=On
```

For a local HTTP server, use `APP_ENV=local`, the local URL, `SESSION_SECURE_COOKIE=false`, and `FORCE_HTTPS=Off`.

On first run, the application creates missing runtime folders, generates an `APP_KEY` if it is blank, and discards configuration cache copied from another server. This prevents stale local paths and cache settings from breaking cPanel deployments.

## Server Requirements

- PHP 8.1 or 8.2
- MySQL or MariaDB
- Standard PHP extensions: `pdo_mysql`, `mbstring`, `openssl`, `curl`, `fileinfo`, `xml`, and `zip`

Set `storage/` and `bootstrap/cache/` to writable (typically permission `775`) in cPanel.

## cPanel Deployment

1. This legacy project uses the root `index.php` as its entry point, so set the domain document root to the project root. Do not point it at the `public/` uploads directory.
2. Set the production values above in the server `.env`; use the canonical HTTPS domain without a trailing slash for `APP_URL`.
3. If LiteSpeed Cache is enabled in cPanel, purge its existing cache after upload and exclude dynamic PHP/Laravel routes from caching. Laravel web responses now send no-cache headers because they contain per-session CSRF tokens.
4. Clear any copied Laravel cache with `php artisan optimize:clear` when SSH/Terminal is available. If it is not, delete only files inside `bootstrap/cache/`, keeping `.gitignore` if present.
