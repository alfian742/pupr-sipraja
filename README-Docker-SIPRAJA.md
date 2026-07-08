# Konfigurasi Docker Terbaru - SI PRAJA

Paket ini berisi konfigurasi Docker terbaru untuk deployment SI PRAJA pada server yang sudah memakai Apache sebagai web server utama.

## Skema Deployment

```text
User / Browser
  ↓
sipraja.lomboktengahkab.go.id
  ↓
Apache host server :80 / :443
  ↓ reverse proxy
http://127.0.0.1:8080
  ↓
Nginx Docker SIPRAJA
  ↓
PHP-FPM Laravel + MySQL Docker
```

Apache tetap memakai port `80/443`. Nginx Docker tidak memakai port publik `80`, tetapi memakai port lokal:

```yaml
ports:
  - "127.0.0.1:8080:80"
```

## File dalam Paket

```text
.env.example
docker-compose.yml
.dockerignore
Makefile
docker/nginx/default.conf
docker/php/Dockerfile
docker/php/php.ini
docker/php/opcache.ini
docker/mysql/my.cnf
apache/sipraja-http.conf
apache/sipraja-https.example.conf
README-Docker-SIPRAJA.md
```

## Cara Pakai Setelah Clone Repo

Masuk ke folder project:

```bash
cd /home/dpur/sipraja
```

Extract file ZIP ini ke root project:

```bash
unzip -o /path/ke/sipraja-docker-config-terbaru.zip -d /home/dpur/sipraja
```

Buat file `.env` dari `.env.example`:

```bash
cp .env.example .env
nano .env
```

Isi password asli pada `.env`:

```env
DB_PASSWORD=password_database_asli
MYSQL_ROOT_PASSWORD=password_root_mysql_asli
MAIL_PASSWORD="password_email_asli"
```

Jalankan Docker:

```bash
docker compose up -d --build
```

Install dependency Laravel:

```bash
docker compose exec app composer install --no-dev --optimize-autoloader
```

Generate key:

```bash
docker compose exec app php artisan key:generate --force
```

Migrasi database:

```bash
docker compose exec app php artisan migrate --force
```

Buat storage link:

```bash
docker compose exec app php artisan storage:link
```

Build asset frontend:

```bash
docker run --rm -v "$PWD":/app -w /app node:22-alpine sh -lc "npm ci && npm run build"
```

Clear dan optimize Laravel:

```bash
docker compose exec app php artisan optimize:clear
docker compose exec app php artisan optimize
docker compose exec app php artisan queue:restart
```

## Konfigurasi Apache Host

Aktifkan module Apache:

```bash
sudo a2enmod proxy
sudo a2enmod proxy_http
sudo a2enmod headers
sudo a2enmod rewrite
sudo systemctl restart apache2
```

Untuk HTTP biasa:

```bash
sudo cp apache/sipraja-http.conf /etc/apache2/sites-available/sipraja.conf
sudo a2ensite sipraja.conf
sudo apachectl configtest
sudo systemctl reload apache2
```

Untuk HTTPS, gunakan `apache/sipraja-https.example.conf` sebagai contoh dan sesuaikan path SSL certificate.

## Tes Setelah Running

Cek container:

```bash
docker compose ps
```

Tes Nginx Docker dari server:

```bash
curl -I http://127.0.0.1:8080
```

Tes lewat Apache dengan Host header:

```bash
curl -I -H "Host: sipraja.lomboktengahkab.go.id" http://127.0.0.1
```

Lihat log:

```bash
docker compose logs -f nginx
docker compose logs -f app
```

## Catatan Penting

- Jangan push file `.env` ke GitHub.
- Password database dan email hanya diisi di `.env` server.
- Jika Apache sudah memakai HTTPS, gunakan `APP_URL=https://sipraja.lomboktengahkab.go.id`.
- Jika HTTPS belum aktif, sementara bisa pakai `APP_URL=http://sipraja.lomboktengahkab.go.id`.

## SEO: robots.txt dan Sitemap

File `public/robots.txt` mengizinkan halaman publik tetap diindeks, tetapi memblokir crawler dari area internal berikut:

```text
/dashboard
/dashboard/
/ikli-survey/dashboard
/ikli-survey/dashboard/
```

File tersebut juga mengarahkan crawler ke:

```text
https://sipraja.lomboktengahkab.go.id/sitemap.xml
```

Sitemap dibuat secara dinamis melalui route Laravel:

```php
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
```

Sitemap hanya memuat halaman publik, artikel blog berstatus `published`, kategori blog aktif, dan dokumen pusat unduhan berstatus `publish`. Route dashboard, autentikasi, API dashboard, profil user, dan halaman internal tidak dimasukkan ke sitemap.

Nginx Docker juga menambahkan header berikut pada area dashboard sebagai lapisan proteksi tambahan terhadap indexing:

```nginx
X-Robots-Tag: noindex, nofollow, noarchive
```

## phpMyAdmin

phpMyAdmin ditambahkan sebagai service Docker terpisah bernama `phpmyadmin`. Service ini tidak membuka port langsung ke publik dan hanya dapat dilewati melalui Nginx Docker pada path:

```text
/phpmyadmin/
```

Kontrol akses dilakukan lewat `.env`:

```env
PHPMYADMIN_ENABLED=false
PHPMYADMIN_UPLOAD_LIMIT=512M
```

Perilaku akses:

- `PHPMYADMIN_ENABLED=true` → `http://127.0.0.1:8080/phpmyadmin/` atau `https://sipraja.lomboktengahkab.go.id/phpmyadmin/` dapat menampilkan phpMyAdmin.
- `PHPMYADMIN_ENABLED=false` → semua akses ke `/phpmyadmin` dan `/phpmyadmin/` dikembalikan sebagai HTTP `404` dari Nginx, bukan dari aplikasi Laravel.

Setelah mengubah nilai `PHPMYADMIN_ENABLED`, restart Nginx agar template konfigurasi Nginx dibentuk ulang:

```bash
docker compose up -d nginx
# atau
docker compose restart nginx
```

Tes akses phpMyAdmin saat nonaktif:

```bash
curl -I http://127.0.0.1:8080/phpmyadmin/
# Harus HTTP/1.1 404 Not Found
```

Tes akses phpMyAdmin saat aktif:

```bash
PHPMYADMIN_ENABLED=true docker compose up -d nginx phpmyadmin
curl -I http://127.0.0.1:8080/phpmyadmin/
# Harus bukan 404 dari Nginx; biasanya 200 atau respons login phpMyAdmin
```

## Tes SEO Setelah Deployment

```bash
curl -s http://127.0.0.1:8080/robots.txt
curl -I http://127.0.0.1:8080/sitemap.xml
curl -I http://127.0.0.1:8080/dashboard
curl -I http://127.0.0.1:8080/ikli-survey/dashboard
```

Untuk dashboard, pastikan header berikut muncul:

```text
X-Robots-Tag: noindex, nofollow, noarchive
```


### Catatan Deployment Template Storage

Direktori berikut **wajib ikut terdeploy** karena berisi file template yang digunakan aplikasi:

```text
storage/app/templates/**
```

Konfigurasi `.gitignore` dan `.dockerignore` sudah disesuaikan agar hanya folder template tersebut yang masuk source/deployment artifact. Folder runtime lain di `storage/app`, seperti file export/import sementara, cache, session, dan log, tetap tidak diperlakukan sebagai source code.
