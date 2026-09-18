# Laravel Backend Boilerplate

Boilerplate backend berbasis **Laravel 12** dengan modul **Auth** (JWT): register, login, me, refresh, dan change password. Tanpa verifikasi email.

## Prasyarat

Pilih salah satu cara menjalankan:

### Opsi A — Tanpa Docker (local machine)
- PHP `^8.2`
- Composer
- MySQL 8 (atau kompatibel)

### Opsi B — Dengan Docker (disarankan)
- Docker + Docker Compose

## Inisiasi (first time)

1. Clone repository ini.
2. Masuk ke folder project.
3. Siapkan file environment:
   - Copy `.env.example` menjadi `.env`
   - Atur konfigurasi DB (lihat bagian **Konfigurasi**)
   - Generate `JWT_SECRET` dengan `php artisan jwt:secret`

## Setup & Menjalankan (Dengan Docker)

`docker-compose.yml` sudah menyiapkan:
- App: `http://localhost:8001`
- MySQL: `localhost:3309` (port host) → `3306` (di container)

Langkah-langkah:

1. Build dan jalankan container:

   ```bash
   docker compose up -d --build
   ```

2. Install dependency & setup Laravel (di dalam container):

   ```bash
   docker compose exec app composer install
   docker compose exec app php artisan key:generate
   docker compose exec app php artisan jwt:secret
   docker compose exec app php artisan migrate --force
   ```

3. (Opsional) Jika ingin menjalankan Laravel Reverb secara manual di dalam container, bisa gunakan perintah:
   
   ```bash
   docker compose exec app php artisan reverb:start
   ```
   Atau, untuk deployment/production, disarankan menjalankan Reverb lewat Supervisor (lihat petunjuk lengkap di `install-reverb-server.md`).

Akses aplikasi:
- `http://localhost:8001`

## Setup & Menjalankan (Tanpa Docker)

1. Install dependency PHP:

   ```bash
   composer install
   ```

2. Buat `.env`, generate key & JWT secret, lalu migrasi DB:

   ```bash
   copy .env.example .env
   php artisan key:generate
   php artisan jwt:secret
   php artisan migrate
   ```

3. Jalankan server:

   ```bash
   php artisan serve
   ```

4. (Opsional) Menjalankan Laravel Reverb secara manual:

   ```
   php artisan reverb:start
   ```

   Atau, untuk production/deployment, gunakan Supervisor (lihat `install-reverb-server.md`).

## Konfigurasi

### Database (Docker)
Jika mengikuti `docker-compose.yml`, gunakan konfigurasi ini di `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=app_db
DB_USERNAME=root
DB_PASSWORD=123
```

### Database (Non-Docker)
Sesuaikan `.env` dengan MySQL lokal kamu (biasanya `DB_HOST=127.0.0.1` dan `DB_PORT=3306`).

### Menjalankan Reverb (Websocket Backend)
Untuk fitur websocket (Laravel Reverb), pastikan environment Reverb sudah dikonfigurasi di file `.env` seperti berikut:

```env
BROADCAST_CONNECTION=reverb

REVERB_APP_ID=xxx
REVERB_APP_KEY=xxx
REVERB_APP_SECRET=xxx
REVERB_HOST=127.0.0.1
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

Untuk panduan lebih lengkap setup & konfigurasi Reverb via Supervisor/NGINX di server, baca file [install-reverb-server.md](install-reverb-server.md).

## Endpoint Auth

| Method | Endpoint                      | Keterangan                  | Butuh JWT |
|--------|--------------------------------|------------------------------|-----------|
| POST   | `/api/auth/register`           | Registrasi user baru         | Tidak     |
| POST   | `/api/auth/login`              | Login                        | Tidak     |
| GET    | `/api/auth/me`                 | Data user yang sedang login  | Ya        |
| POST   | `/api/auth/refresh`            | Refresh JWT token            | Tidak     |
| PATCH  | `/api/auth/change-password`    | Ganti password                | Ya        |

## Testing

Test (Unit & Feature) berjalan di atas SQLite in-memory (`DB_CONNECTION=sqlite`, `DB_DATABASE=:memory:`), lihat konfigurasi di `phpunit.xml`. Karena itu **tidak perlu** setup database MySQL terpisah untuk testing, tapi ekstensi PHP `pdo_sqlite` harus aktif (cek/aktifkan di `php.ini`).

Menjalankan seluruh test:

```bash
composer test
```

atau langsung lewat Artisan/PHPUnit:

```bash
php artisan test
# atau
vendor/bin/phpunit
```

Menjalankan satu file test tertentu:

```bash
php artisan test tests/Feature/Auth/LoginTest.php
```

Menjalankan test berdasarkan nama method/class (filter):

```bash
php artisan test --filter=test_user_can_login_with_valid_credentials
php artisan test --filter=LoginTest
```

Menjalankan hanya test suite tertentu (`Unit` atau `Feature`, lihat `phpunit.xml`):

```bash
php artisan test --testsuite=Feature
```
