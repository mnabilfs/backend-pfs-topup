## Daftar Isi
6. [Konfigurasi Environment](#konfigurasi-environment)

## Konfigurasi Environment

### File `.env` Penting

```env
# Application
APP_NAME=Laravel
APP_ENV=local  # production untuk deploy
APP_DEBUG=true  # false untuk production
APP_URL=http://localhost  # ganti dengan domain production

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=topup_db
DB_USERNAME=root
DB_PASSWORD=

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Cache
CACHE_STORE=database
QUEUE_CONNECTION=database

# CORS (untuk production)
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1,frontend-pfs-topup.vercel.app
```

### CORS Configuration
File `config/cors.php` sudah dikonfigurasi untuk:
- Local development: `localhost:5173`, `localhost:3000`
- Production: `https://frontend-pfs-topup.vercel.app`

**Penting:** Tambahkan domain frontend production ke array `allowed_origins`.
