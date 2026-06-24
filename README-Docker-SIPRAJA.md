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
