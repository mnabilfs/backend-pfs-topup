## Daftar Isi
5. [Instalasi dan Setup](#instalasi-dan-setup)

## Instalasi dan Setup

### Prerequisites
```bash
# Pastikan sudah terinstall:
- PHP >= 8.2
- Composer
- MySQL >= 8.0
- Node.js (opsional, untuk frontend)
```

### Langkah Instalasi

1. **Clone Repository**
```bash
git clone <repository-url>
cd backend-pfs-topup
```

2. **Install Dependencies**
```bash
composer install
```

3. **Setup Environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Konfigurasi Database**
Edit file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=topup_db
DB_USERNAME=root
DB_PASSWORD=
```

5. **Jalankan Migration**
```bash
php artisan migrate
```

6. **Create Storage Symlink**
```bash
php artisan storage:link
```

7. **Set Permission** (Linux/Mac)
```bash
chmod -R 775 storage bootstrap/cache
```

8. **Jalankan Server**
```bash
php artisan serve
# Server berjalan di http://localhost:8000
```
