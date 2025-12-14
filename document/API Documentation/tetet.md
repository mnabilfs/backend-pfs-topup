# API Documentation - Backend PFS TopUp

## 📡 Base URL
- **Development:** `http://localhost:8000/api`
- **Production:** `https://your-railway-domain.railway.app/api`

## 🔐 Authentication
API menggunakan Laravel Sanctum untuk autentikasi berbasis token.

### Headers untuk Protected Endpoints
```
Authorization: Bearer {your_access_token}
Content-Type: application/json
Accept: application/json
```

---

## 🚪 Authentication Endpoints

### 1. Register
Mendaftarkan user baru.

**Endpoint:** `POST /register`  
**Auth Required:** No

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "role": "user"  // optional, default: "user"
}
```

**Response Success (200):**
```json
{
  "message": "User registered successfully",
  "access_token": "1|abc123token...",
  "token_type": "Bearer",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "user",
    "avatar": null,
    "created_at": "2025-12-09T10:00:00.000000Z",
    "updated_at": "2025-12-09T10:00:00.000000Z"
  }
}
```

---

### 2. Login
Login user yang sudah terdaftar.

**Endpoint:** `POST /login`  
**Auth Required:** No

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response Success (200):**
```json
{
  "message": "Login successful",
  "access_token": "2|xyz789token...",
  "token_type": "Bearer",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "user",
    "avatar": null
  }
}
```

**Response Error (401):**
```json
{
  "message": "Invalid credentials"
}
```

---

### 3. Logout
Logout user dan revoke token.

**Endpoint:** `POST /logout`  
**Auth Required:** Yes

**Response Success (200):**
```json
{
  "message": "Logout successful"
}
```

---

### 4. Update Profile
Update data profil user (nama, email, password, avatar).

**Endpoint:** `POST /user/profile/update`  
**Auth Required:** Yes  
**Content-Type:** `multipart/form-data`

**Request Body (FormData):**
```
name: "John Updated"
email: "john.updated@example.com"
password: "newpassword123"  // optional
avatar: [File]  // optional, max 2MB
```

**Response Success (200):**
```json
{
  "message": "Profile updated successfully",
  "user": {
    "id": 1,
    "name": "John Updated",
    "email": "john.updated@example.com",
    "role": "user",
    "avatar": "http://localhost:8000/storage/avatars/1234567890_abc.jpg"
  }
}
```

**Validation Rules:**
- name: required, string, max 255 chars
- email: required, email, unique
- password: nullable, min 6 chars
- avatar: nullable, image (jpg, jpeg, png, gif), max 2MB

---

## 🎮 Games Endpoints

### 1. Get All Games
Ambil semua data game (Public).

**Endpoint:** `GET /games`  
**Auth Required:** No

**Response Success (200):**
```json
[
  {
    "id": 1,
    "name": "Mobile Legends",
    "publisher": "Moonton",
    "image_url": "https://example.com/mlbb.jpg",
    "banner_url": "https://example.com/mlbb-banner.jpg",
    "created_at": "2025-12-09T10:00:00.000000Z",
    "updated_at": "2025-12-09T10:00:00.000000Z"
  }
]
```

---

### 2. Get Single Game
Ambil detail game dengan produk-produknya.

**Endpoint:** `GET /games/{id}`  
**Auth Required:** No

**Response Success (200):**
```json
{
  "id": 1,
  "name": "Mobile Legends",
  "publisher": "Moonton",
  "image_url": "https://example.com/mlbb.jpg",
  "banner_url": "https://example.com/mlbb-banner.jpg",
  "products": [
    {
      "id": 1,
      "game_id": 1,
      "name": "100 Diamonds",
      "price": 25000,
      "image_url": "https://example.com/diamond.jpg"
    }
  ],
  "created_at": "2025-12-09T10:00:00.000000Z",
  "updated_at": "2025-12-09T10:00:00.000000Z"
}
```

---

### 3. Create Game (Admin Only)
Tambah game baru.

**Endpoint:** `POST /games`  
**Auth Required:** Yes (Admin)

**Request Body:**
```json
{
  "name": "Free Fire",
  "publisher": "Garena",
  "image_url": "https://example.com/ff.jpg",
  "banner_url": "https://example.com/ff-banner.jpg"
}
```

**Response Success (201):**
```json
{
  "id": 2,
  "name": "Free Fire",
  "publisher": "Garena",
  "image_url": "https://example.com/ff.jpg",
  "banner_url": "https://example.com/ff-banner.jpg",
  "created_at": "2025-12-09T10:00:00.000000Z",
  "updated_at": "2025-12-09T10:00:00.000000Z"
}
```

---

### 4. Update Game (Admin Only)
Update data game.

**Endpoint:** `PUT /games/{id}`  
**Auth Required:** Yes (Admin)

**Request Body:**
```json
{
  "name": "Free Fire MAX",
  "publisher": "Garena International",
  "image_url": "https://example.com/ffmax.jpg",
  "banner_url": "https://example.com/ffmax-banner.jpg"
}
```

**Response Success (200):**
```json
{
  "id": 2,
  "name": "Free Fire MAX",
  "publisher": "Garena International",
  "image_url": "https://example.com/ffmax.jpg",
  "banner_url": "https://example.com/ffmax-banner.jpg",
  "updated_at": "2025-12-09T11:00:00.000000Z"
}
```

---

### 5. Delete Game (Admin Only)
Hapus game.

**Endpoint:** `DELETE /games/{id}`  
**Auth Required:** Yes (Admin)

**Response Success (200):**
```json
{
  "message": "Game deleted successfully"
}
```

---

## 💎 Products Endpoints

### 1. Get All Products
Ambil semua produk top-up (Public).

**Endpoint:** `GET /products`  
**Auth Required:** No

**Response Success (200):**
```json
[
  {
    "id": 1,
    "game_id": 1,
    "name": "100 Diamonds",
    "price": 25000,
    "image_url": "https://example.com/diamond.jpg",
    "created_at": "2025-12-09T10:00:00.000000Z",
    "updated_at": "2025-12-09T10:00:00.000000Z"
  }
]
```

---

### 2. Get Single Product
Ambil detail produk dengan data game.

**Endpoint:** `GET /products/{id}`  
**Auth Required:** No

**Response Success (200):**
```json
{
  "id": 1,
  "game_id": 1,
  "name": "100 Diamonds",
  "price": 25000,
  "image_url": "https://example.com/diamond.jpg",
  "game": {
    "id": 1,
    "name": "Mobile Legends",
    "publisher": "Moonton",
    "image_url": "https://example.com/mlbb.jpg"
  },
  "created_at": "2025-12-09T10:00:00.000000Z",
  "updated_at": "2025-12-09T10:00:00.000000Z"
}
```

---

### 3. Create Product (Admin Only)
Tambah produk baru.

**Endpoint:** `POST /products`  
**Auth Required:** Yes (Admin)

**Request Body:**
```json
{
  "game_id": 1,
  "name": "500 Diamonds",
  "price": 100000,
  "image_url": "https://example.com/diamond500.jpg"
}
```

**Response Success (201):**
```json
{
  "id": 2,
  "game_id": 1,
  "name": "500 Diamonds",
  "price": 100000,
  "image_url": "https://example.com/diamond500.jpg",
  "created_at": "2025-12-09T10:00:00.000000Z",
  "updated_at": "2025-12-09T10:00:00.000000Z"
}
```

**Validation Rules:**
- game_id: required, must exist in games table
- name: required, string, max 255 chars
- price: required, integer, min 0
- image_url: nullable, string

---

### 4. Update Product (Admin Only)
Update data produk.

**Endpoint:** `PUT /products/{id}`  
**Auth Required:** Yes (Admin)

**Request Body:**
```json
{
  "game_id": 1,
  "name": "500 Diamonds Promo",
  "price": 95000,
  "image_url": "https://example.com/diamond500-promo.jpg"
}
```

---

### 5. Delete Product (Admin Only)
Hapus produk.

**Endpoint:** `DELETE /products/{id}`  
**Auth Required:** Yes (Admin)

**Response Success (200):**
```json
{
  "message": "Product deleted successfully"
}
```

---

## 🎨 Banners Endpoints

### 1. Get Active Banners (Public)
Ambil banner yang aktif untuk homepage.

**Endpoint:** `GET /banners`  
**Auth Required:** No

**Response Success (200):**
```json
[
  {
    "id": 1,
    "title": "Promo Ramadan",
    "image_url": "https://example.com/banner1.jpg",
    "order": 1,
    "is_active": true,
    "created_at": "2025-12-09T10:00:00.000000Z",
    "updated_at": "2025-12-09T10:00:00.000000Z"
  }
]
```

---

### 2. Get All Banners (Admin)
Ambil semua banner termasuk yang tidak aktif.

**Endpoint:** `GET /banners/all`  
**Auth Required:** Yes (Admin)

**Response Success (200):**
```json
[
  {
    "id": 1,
    "title": "Promo Ramadan",
    "image_url": "https://example.com/banner1.jpg",
    "order": 1,
    "is_active": true
  },
  {
    "id": 2,
    "title": "Banner Nonaktif",
    "image_url": "https://example.com/banner2.jpg",
    "order": 2,
    "is_active": false
  }
]
```

---

### 3. Create Banner (Admin Only)
Tambah banner baru.

**Endpoint:** `POST /banners`  
**Auth Required:** Yes (Admin)

**Request Body:**
```json
{
  "title": "Promo Tahun Baru",
  "image_url": "https://example.com/newyear.jpg",
  "order": 1,
  "is_active": true
}
```

**Response Success (201):**
```json
{
  "id": 3,
  "title": "Promo Tahun Baru",
  "image_url": "https://example.com/newyear.jpg",
  "order": 1,
  "is_active": true,
  "created_at": "2025-12-09T10:00:00.000000Z",
  "updated_at": "2025-12-09T10:00:00.000000Z"
}
```

---

### 4. Update Banner (Admin Only)
Update banner.

**Endpoint:** `PUT /banners/{id}`  
**Auth Required:** Yes (Admin)

---

### 5. Delete Banner (Admin Only)
Hapus banner.

**Endpoint:** `DELETE /banners/{id}`  
**Auth Required:** Yes (Admin)

**Response Success (200):**
```json
{
  "message": "Banner deleted successfully"
}
```

---

## 🎵 Background Music Endpoints

### 1. Get Active Music (Public)
Ambil musik yang sedang aktif.

**Endpoint:** `GET /background-music/active`  
**Auth Required:** No

**Response Success (200):**
```json
{
  "id": 1,
  "title": "Background Melody",
  "artist": "Composer Name",
  "audio_url": "http://localhost:8000/storage/music/1234567890_abc.mp3",
  "is_active": true,
  "order": 1,
  "created_at": "2025-12-09T10:00:00.000000Z",
  "updated_at": "2025-12-09T10:00:00.000000Z"
}
```

---

### 2. Get All Music (Admin)
Ambil semua musik.

**Endpoint:** `GET /background-music`  
**Auth Required:** Yes (Admin)

**Response Success (200):**
```json
[
  {
    "id": 1,
    "title": "Background Melody",
    "artist": "Composer Name",
    "audio_url": "http://localhost:8000/storage/music/1234567890_abc.mp3",
    "is_active": true,
    "order": 1
  }
]
```

---

### 3. Upload Music (Admin Only)
Upload musik baru.

**Endpoint:** `POST /background-music`  
**Auth Required:** Yes (Admin)  
**Content-Type:** `multipart/form-data`

**Request Body (FormData):**
```
title: "Epic Gaming Music"
artist: "John Composer"  // optional
audio: [File]  // required, mp3/wav/ogg, max 10MB
is_active: true  // optional
```

**Response Success (201):**
```json
{
  "message": "Background music uploaded successfully",
  "data": {
    "id": 2,
    "title": "Epic Gaming Music",
    "artist": "John Composer",
    "audio_url": "http://localhost:8000/storage/music/1234567890_xyz.mp3",
    "is_active": true,
    "order": 2,
    "created_at": "2025-12-09T10:00:00.000000Z",
    "updated_at": "2025-12-09T10:00:00.000000Z"
  }
}
```

**Validation Rules:**
- title: required, string, max 255 chars
- artist: nullable, string, max 255 chars
- audio: required, file (mp3, wav, ogg), max 10MB
- is_active: boolean

**Note:** Jika `is_active` true, musik lain otomatis jadi non-aktif.

---

### 4. Update Music (Admin Only)
Update data atau file musik.

**Endpoint:** `PUT /background-music/{id}`  
**Auth Required:** Yes (Admin)  
**Content-Type:** `multipart/form-data`

**Request Body (FormData):**
```
title: "Updated Title"  // optional
artist: "Updated Artist"  // optional
audio: [File]  // optional, akan replace file lama
is_active: false  // optional
```

**Response Success (200):**
```json
{
  "message": "Background music updated successfully",
  "data": {
    "id": 2,
    "title": "Updated Title",
    "artist": "Updated Artist",
    "audio_url": "http://localhost:8000/storage/music/new_file.mp3",
    "is_active": false,
    "order": 2
  }
}
```

---

### 5. Set Active Music (Admin Only)
Aktifkan musik tertentu (musik lain jadi non-aktif).

**Endpoint:** `POST /background-music/{id}/activate`  
**Auth Required:** Yes (Admin)

**Response Success (200):**
```json
{
  "message": "Background music activated successfully",
  "data": {
    "id": 2,
    "title": "Epic Gaming Music",
    "is_active": true
  }
}
```

---

### 6. Delete Music (Admin Only)
Hapus musik dan file audionya.

**Endpoint:** `DELETE /background-music/{id}`  
**Auth Required:** Yes (Admin)

**Response Success (200):**
```json
{
  "message": "Background music deleted successfully"
}
```

---

## 🎮 Sold Accounts Endpoints

### 1. Get Active Sold Accounts (Public)
Ambil akun yang dijual dan aktif.

**Endpoint:** `GET /sold-accounts`  
**Auth Required:** No (Public), Optional (Admin)

**Behavior:**
- **Public/Unauthenticated:** Hanya akun dengan `is_active: true`
- **Admin/Authenticated:** Semua akun (aktif & non-aktif)

**Response Success (200):**
```json
[
  {
    "id": 1,
    "title": "MLBB Sultan Account",
    "description": "Akun sultan dengan 500+ skin",
    "price": 5000000,
    "image_url": "https://example.com/account1.jpg",
    "gallery": [
      "https://example.com/gallery1.jpg",
      "https://example.com/gallery2.jpg"
    ],
    "is_active": true,
    "order": 1,
    "created_at": "2025-12-09T10:00:00.000000Z",
    "updated_at": "2025-12-09T10:00:00.000000Z"
  }
]
```

---

### 2. Get Single Sold Account
Detail akun yang dijual.

**Endpoint:** `GET /sold-accounts/{id}`  
**Auth Required:** No

**Response Success (200):**
```json
{
  "id": 1,
  "title": "MLBB Sultan Account",
  "description": "Akun sultan dengan 500+ skin, rank Mythic",
  "price": 5000000,
  "image_url": "https://example.com/account1.jpg",
  "gallery": [
    "https://example.com/gallery1.jpg",
    "https://example.com/gallery2.jpg",
    "https://example.com/gallery3.jpg"
  ],
  "is_active": true,
  "order": 1,
  "created_at": "2025-12-09T10:00:00.000000Z",
  "updated_at": "2025-12-09T10:00:00.000000Z"
}
```

**Response Error (404):**
```json
{
  "error": "Akun tidak ditemukan",
  "id": 999
}
```

---

### 3. Create Sold Account (Admin Only)
Tambah akun dijual baru.

**Endpoint:** `POST /sold-accounts`  
**Auth Required:** Yes (Admin)

**Request Body:**
```json
{
  "title": "Free Fire Pro Account",
  "description": "Akun FF lengkap dengan bundle",
  "price": 3000000,
  "image_url": "https://example.com/ff-account.jpg",
  "gallery": [
    "https://example.com/ff-gal1.jpg",
    "https://example.com/ff-gal2.jpg"
  ],
  "order": 2,
  "is_active": true
}
```

**Response Success (201):**
```json
{
  "id": 2,
  "title": "Free Fire Pro Account",
  "description": "Akun FF lengkap dengan bundle",
  "price": 3000000,
  "image_url": "https://example.com/ff-account.jpg",
  "gallery": [
    "https://example.com/ff-gal1.jpg",
    "https://example.com/ff-gal2.jpg"
  ],
  "is_active": true,
  "order": 2,
  "created_at": "2025-12-09T10:00:00.000000Z",
  "updated_at": "2025-12-09T10:00:00.000000Z"
}
```

**Validation Rules:**
- title: required, string, max 255 chars
- description: nullable, text
- price: required, integer, min 0
- image_url: required, string
- gallery: nullable, array of strings
- order: nullable, integer
- is_active: nullable, boolean

---

### 4. Update Sold Account (Admin Only)
Update data akun.

**Endpoint:** `PUT /sold-accounts/{id}`  
**Auth Required:** Yes (Admin)

**Request Body:**
```json
{
  "title": "Updated Title",
  "price": 4500000,
  "is_active": false
}
```

**Response Success (200):**
```json
{
  "id": 2,
  "title": "Updated Title",
  "description": "Akun FF lengkap dengan bundle",
  "price": 4500000,
  "is_active": false,
  "updated_at": "2025-12-09T11:00:00.000000Z"
}
```

---

### 5. Delete Sold Account (Admin Only)
Hapus akun yang dijual.

**Endpoint:** `DELETE /sold-accounts/{id}`  
**Auth Required:** Yes (Admin)

**Response Success (200):**
```json
{
  "message": "Akun berhasil dihapus"
}
```

**Response Error (404):**
```json
{
  "error": "Akun tidak ditemukan",
  "id": 999
}
```

---

## 🔒 Authorization & Errors

### Authorization Errors

**401 Unauthenticated:**
```json
{
  "message": "Unauthenticated."
}
```

**403 Forbidden (Role tidak sesuai):**
```json
{
  "message": "Unauthorized."
}
```

### Common Error Responses

**422 Validation Error:**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": [
      "The email has already been taken."
    ],
    "password": [
      "The password must be at least 6 characters."
    ]
  }
}
```

**404 Not Found:**
```json
{
  "message": "No query results for model [App\\Models\\Game] 999"
}
```

**500 Internal Server Error:**
```json
{
  "message": "Server error occurred",
  "error": "Error detail..."
}
```

---

## 📝 Notes

### File Upload Guidelines
- Gunakan `FormData` untuk request dengan file
- Set header `Content-Type: multipart/form-data`
- Max file size sudah divalidasi di backend

### Token Management
- Token disimpan di client (localStorage/cookies)
- Kirim token di header `Authorization: Bearer {token}`
- Token tidak expire (Sanctum default)
- Logout akan revoke token

### CORS
- CORS sudah dikonfigurasi untuk localhost dan production
- Pastikan frontend domain ada di `config/cors.php`

---

**Last Updated:** December 2025  
**Version:** 1.0.0
