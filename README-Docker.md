# Docker Setup - SI PRAJA Laravel

Konfigurasi ini disesuaikan untuk project SI PRAJA:

- Laravel 11 dan PHP `^8.2`, memakai image PHP 8.3 FPM.
- Database utama MySQL.
- Queue menggunakan `database`, karena export/import Excel di controller memakai job queue.
- Frontend memakai Vite/Tailwind.
- File upload project banyak memakai `public_path('uploads/...')`, sehingga root project di-bind ke container.
- Maatwebsite Excel membutuhkan ekstensi PHP seperti `zip`, `gd`, `xml`, `mbstring`, dan `pcntl` untuk queue.

## Service

| Service | Fungsi | URL/Port |
|---|---|---|
| app | PHP 8.3 FPM + Composer | internal |
| nginx | Web server Laravel | http://localhost:8000 |
| mysql | MySQL 8 | localhost:3307 |
| queue | Laravel queue worker database | internal |
| vite | Vite dev server | http://localhost:5173 |
| phpmyadmin | Kelola database | http://localhost:8081 |
| mailpit | Testing email | http://localhost:8025 |

## Cara pakai pertama kali

Dari root project:

```bash
cp .env.docker .env
docker compose up -d --build
```

Install dependency Laravel:

```bash
docker compose exec app composer install
```

Generate key:

```bash
docker compose exec app php artisan key:generate
```

Migrasi database:

```bash
docker compose exec app php artisan migrate
```

Buat storage link jika diperlukan:

```bash
docker compose exec app php artisan storage:link
```

Bersihkan cache:

```bash
docker compose exec app php artisan optimize:clear
```

Akses aplikasi:

```text
http://localhost:8000
```

## Akses database

Dari laptop:

```text
Host     : 127.0.0.1
Port     : 3307
Database : sipraja
Username : sipraja
Password : sipraja_secret
```

Dari dalam container Laravel:

```text
DB_HOST=mysql
DB_PORT=3306
```

phpMyAdmin:

```text
http://localhost:8081
```

## Import database backup SQL

Contoh jika punya file `backup.sql` di root project:

```bash
docker compose exec -T mysql mysql -usipraja -psipraja_secret sipraja < backup.sql
```

## Command harian

```bash
# Masuk container PHP
docker compose exec app bash

# Artisan
docker compose exec app php artisan migrate
docker compose exec app php artisan optimize:clear
docker compose exec app php artisan queue:restart

# Composer
docker compose exec app composer install
docker compose exec app composer dump-autoload

# Build asset untuk deploy
docker compose run --rm vite npm run build

# Log
docker compose logs -f app
docker compose logs -f nginx
docker compose logs -f queue
```

Jika memakai Makefile:

```bash
make up
make composer-install
make key
make migrate
make clear
```

## Catatan Vite

`vite.config.js` sudah ditambah konfigurasi server agar Vite bisa diakses dari browser ketika berjalan di Docker.

## Sebelum deploy ke shared hosting

Docker biasanya hanya dipakai di lokal. Untuk shared hosting, jalankan build lokal lalu upload project seperti biasa:

```bash
docker compose exec app composer install --no-dev --optimize-autoloader
docker compose run --rm vite npm run build
docker compose exec app php artisan optimize:clear
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache
```

Upload folder project, `vendor`, dan `public/build`. Jangan upload `node_modules`.

## Sebelum deploy ke VPS dengan Docker

Untuk VPS, konfigurasi ini bisa dijadikan dasar. Namun untuk production sebaiknya:

- `APP_ENV=production`
- `APP_DEBUG=false`
- password MySQL diganti kuat
- gunakan domain asli pada `APP_URL`
- Nginx production dipasang SSL lewat reverse proxy/Caddy/Traefik/Nginx host
- queue worker tetap dijalankan karena export/import Excel bergantung pada queue


## Jika build Dockerfile error pada docker-php-ext-install / pecl

Versi Dockerfile ini sudah dibuat lebih stabil dengan menghapus instalasi Redis PECL dan tidak meng-compile ulang ekstensi XML bawaan PHP. Project ini memakai `QUEUE_CONNECTION=database`, jadi Redis tidak wajib.

Jika masih ada error, jalankan build dengan log lengkap:

```bash
docker compose build --no-cache --progress=plain app
```

Lalu cek modul PHP setelah container berhasil jalan:

```bash
docker compose exec app php -m
docker compose exec app composer check-platform-reqs
```
