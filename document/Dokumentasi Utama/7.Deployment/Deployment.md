## Daftar Isi
7. [Deployment](#deployment)

## Deployment

### Railway Deployment

File `nixpacks.toml` sudah dikonfigurasi dengan:

**Setup Phase:**
- PHP 8.2 dengan semua extension yang dibutuhkan

**Install Phase:**
```bash
composer install --no-dev --optimize-autoloader --no-interaction
```

**Build Phase:**
- Clear semua cache Laravel

**Deploy Phase:**
```bash
php artisan storage:link
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

**Start Command:**
```bash
php artisan serve --host=0.0.0.0 --port=${PORT}
```

### Environment Variables di Railway

Tambahkan di Railway Dashboard:
```
APP_ENV=production
APP_DEBUG=false
APP_KEY=<generate-new-key>
APP_URL=<railway-domain>

DB_CONNECTION=mysql
DB_HOST=<railway-mysql-host>
DB_PORT=3306
DB_DATABASE=<db-name>
DB_USERNAME=<db-user>
DB_PASSWORD=<db-password>

SESSION_DRIVER=database
CACHE_STORE=database

SANCTUM_STATEFUL_DOMAINS=<railway-domain>,frontend-pfs-topup.vercel.app
```

### Heroku Deployment (Alternative)

File `Procfile` tersedia:
```
web: sh -c 'php artisan migrate --force && php artisan storage:link --force && php artisan config:cache && php artisan route:cache && php artisan serve --host=0.0.0.0 --port=${PORT}'
```
