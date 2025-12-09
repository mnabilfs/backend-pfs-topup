## Daftar Isi
3. [Struktur Database](#struktur-database)

## Struktur Database

### Tabel: `users`
```
- id (PK)
- name (string)
- email (string, unique)
- password (hashed)
- role (enum: 'user', 'admin')
- avatar (nullable, string)
- created_at, updated_at
```

### Tabel: `games`
```
- id (PK)
- name (string)
- publisher (nullable, string)
- image_url (string)
- banner_url (nullable, string)
- created_at, updated_at
```

### Tabel: `products`
```
- id (PK)
- game_id (FK -> games.id)
- name (string)
- price (integer, min: 0)
- image_url (nullable, string)
- created_at, updated_at
```

### Tabel: `banners`
```
- id (PK)
- title (nullable, string)
- image_url (string)
- order (integer)
- is_active (boolean)
- created_at, updated_at
```

### Tabel: `background_musics`
```
- id (PK)
- title (string)
- artist (nullable, string)
- audio_url (string)
- is_active (boolean)
- order (integer)
- created_at, updated_at
```

### Tabel: `sold_accounts`
```
- id (PK)
- title (string)
- description (nullable, text)
- price (integer, min: 0)
- image_url (string)
- gallery (nullable, JSON array)
- is_active (boolean)
- order (integer)
- created_at, updated_at
```

### Tabel: `sessions`
```
- id (PK, string)
- user_id (nullable, FK)
- ip_address (string, 45 chars)
- user_agent (text)
- payload (text)
- last_activity (integer, indexed)
```
