## Daftar Isi
4. [Fitur Utama](#fitur-utama)

## Fitur Utama

### 1. Authentication System
- **Register:** Pendaftaran user baru dengan role default 'user'
- **Login:** Autentikasi menggunakan email & password
- **Logout:** Revoke token pengguna
- **Profile Update:** Update nama, email, password, dan avatar

### 2. Game Management (Admin Only)
- CRUD lengkap untuk data game
- Relasi dengan produk top-up
- Upload gambar game dan banner

### 3. Product Management (Admin Only)
- CRUD produk top-up
- Terintegrasi dengan game
- Harga dalam format integer (Rupiah)

### 4. Banner System
- Banner dinamis untuk halaman home
- Urutan tampilan bisa diatur
- Status aktif/non-aktif
- Public endpoint untuk frontend

### 5. Background Music
- Upload file audio (mp3, wav, ogg)
- Hanya satu musik aktif pada satu waktu
- Manajemen urutan putar
- Public endpoint untuk mendapatkan musik aktif

### 6. Sold Accounts Catalog
- Katalog akun game yang dijual
- Galeri gambar (multiple images)
- Harga dan deskripsi lengkap
- Filter aktif/non-aktif untuk public

### 7. Role-Based Access Control
- **User Role:** Akses terbatas (profile, view public data)
- **Admin Role:** Full access ke semua management endpoints
