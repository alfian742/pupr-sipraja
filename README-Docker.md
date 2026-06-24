# Docker Production Setup - SI PRAJA Laravel

Dokumen ini digunakan untuk branch **`docker-ready`**, yaitu branch khusus deployment SI PRAJA ke **VPS production** menggunakan Docker.

Branch ini berbeda dari branch `docker` yang dipakai untuk development lokal.

## Ringkasan Konfigurasi

Konfigurasi ini disesuaikan untuk project SI PRAJA:

* Laravel 11 dengan PHP `^8.2`, memakai image PHP 8.3 FPM.
* Web server menggunakan Nginx.
* Database utama menggunakan MySQL 8.
* Queue menggunakan driver `database`.
* Queue dipisahkan menjadi:

  * `exports`
  * `imports`
  * `default`
* Export/import Excel diproses melalui queue worker khusus.
* Frontend Vite/Tailwind dibuild menjadi asset production, bukan dijalankan sebagai dev server.
* Tidak menyertakan service development seperti phpMyAdmin, Mailpit, atau Vite dev server.
* File upload project banyak memakai path lokal Laravel, sehingga root project di-bind ke container.
* Maatwebsite Excel membutuhkan ekstensi PHP seperti `zip`, `gd`, `mbstring`, `intl`, `bcmath`, `pcntl`, dan `pdo_mysql`.

## Service Production

| Service         | Fungsi                       | Akses                       |
| --------------- | ---------------------------- | --------------------------- |
| `app`           | PHP 8.3 FPM + Composer       | internal                    |
| `nginx`         | Web server Laravel           | `http://IP_VPS` atau domain |
| `mysql`         | MySQL 8                      | internal Docker network     |
| `queue_exports` | Worker khusus export Excel   | internal                    |
| `queue_imports` | Worker khusus import Excel   | internal                    |
| `queue_default` | Worker queue default Laravel | internal                    |

Service yang sengaja tidak dipakai di production:

| Service      | Alasan                                   |
| ------------ | ---------------------------------------- |
| `phpMyAdmin` | Tidak disarankan terbuka di production   |
| `mailpit`    | Hanya untuk testing email lokal          |
| `vite`       | Production memakai hasil `npm run build` |

## Struktur File Penting

```text
project/
├── docker-compose.yml
├── .env.example
├── .env
├── docker/
│   ├── nginx/
│   │   └── default.conf
│   ├── php/
│   │   ├── Dockerfile
│   │   ├── php.ini
│   │   └── opcache.ini
│   └── mysql/
│       └── my.cnf
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── composer.json
├── composer.lock
├── package.json
└── package-lock.json
```

## File yang Boleh Dipush ke GitHub

```text
.env.example
docker-compose.yml
docker/
.dockerignore
.gitignore
composer.json
composer.lock
package.json
package-lock.json
app/
bootstrap/
config/
database/
public/
resources/
routes/
```

## File yang Tidak Boleh Dipush ke GitHub

```text
.env
vendor/
node_modules/
storage/logs/
storage/framework/cache/
storage/framework/sessions/
storage/framework/views/
bootstrap/cache/*.php
backup.sql
*.zip
*.rar
*.7z
file upload asli
credential production
password database
password email
```

## Contoh `.env` Production

Copy `.env.example` menjadi `.env` di VPS:

```bash
cp .env.example .env
```

Lalu edit:

```bash
nano .env
```

Bagian penting yang wajib disesuaikan:

```env
APP_NAME="SI PRAJA"
APP_SUBNAME="DPUPR Kabupaten Lombok Tengah"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_TIMEZONE=Asia/Makassar
APP_URL=https://domain-anda.com

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=sipraja
DB_USERNAME=sipraja
DB_PASSWORD=ganti_password_database_yang_kuat
MYSQL_ROOT_PASSWORD=ganti_password_root_mysql_yang_kuat

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
DB_QUEUE_RETRY_AFTER=1500

FILESYSTEM_DISK=local
LOG_LEVEL=error
```

Catatan penting:

* `DB_HOST=mysql` wajib karena Laravel berjalan di container `app`, sedangkan database berjalan di container `mysql`.
* `APP_DEBUG=false` wajib untuk production.
* `DB_QUEUE_RETRY_AFTER=1500` disesuaikan karena worker export/import memakai timeout panjang.
* Jangan menggunakan password contoh di production.

## Queue Worker

Production memakai 3 worker terpisah.

### Queue Export

```bash
php -d memory_limit=512M artisan queue:work database --queue=exports --sleep=1 --tries=3 --timeout=1200 --max-time=3600
```

### Queue Import

```bash
php -d memory_limit=512M artisan queue:work database --queue=imports --sleep=1 --tries=3 --timeout=1200 --max-time=3600
```

### Queue Default

```bash
php -d memory_limit=256M artisan queue:work database --queue=default --sleep=3 --tries=3 --timeout=300 --max-time=3600
```

Karena `exports` dan `imports` memakai `--timeout=1200`, nilai `retry_after` database queue harus lebih besar dari 1200.

Pastikan di `.env`:

```env
DB_QUEUE_RETRY_AFTER=1500
```

Pastikan juga di `config/queue.php` bagian `database` membaca nilai env tersebut:

```php
'retry_after' => (int) env('DB_QUEUE_RETRY_AFTER', 1500),
```

## Persiapan Branch `docker-ready`

Dari branch development Docker:

```bash
git checkout docker
git pull origin docker
```

Buat branch baru 100% berbeda:

```bash
git checkout --orphan docker-ready
git rm -r --cached .
```

Pastikan file production sudah disesuaikan:

```text
docker-compose.yml
.env.example
docker/nginx/default.conf
docker/php/Dockerfile
docker/php/php.ini
docker/php/opcache.ini
docker/mysql/my.cnf
```

Cek konfigurasi Docker Compose:

```bash
docker compose config
```

Tambahkan file ke Git:

```bash
git add .
git status
```

Pastikan `.env` tidak ikut masuk commit.

Commit:

```bash
git commit -m "Prepare docker-ready production branch"
```

Push ke GitHub:

```bash
git push -u origin docker-ready
```

Apabila branch orphan ditolak karena histori berbeda, gunakan:

```bash
git push -u origin docker-ready --force
```

Pastikan push hanya ke branch `docker-ready`, bukan ke `main` atau `docker`.

## Deploy Pertama Kali ke VPS

Masuk ke VPS:

```bash
ssh user@IP_VPS
```

Atau dengan port khusus:

```bash
ssh -p 9967 user@IP_VPS
```

Install dependency dasar:

```bash
sudo apt update
sudo apt install -y ca-certificates curl gnupg git unzip
```

Install Docker:

```bash
curl -fsSL https://get.docker.com | sudo sh
```

Aktifkan Docker:

```bash
sudo systemctl enable docker
sudo systemctl start docker
```

Tambahkan user ke group Docker:

```bash
sudo usermod -aG docker $USER
```

Logout dari VPS, lalu login ulang.

Cek Docker:

```bash
docker --version
docker compose version
```

## Clone Project dari Branch `docker-ready`

Masuk ke folder deployment:

```bash
cd /var/www
```

Clone branch production:

```bash
sudo git clone -b docker-ready https://github.com/USERNAME/NAMA-REPOSITORY.git sipraja
```

Ubah owner folder:

```bash
sudo chown -R $USER:$USER /var/www/sipraja
```

Masuk project:

```bash
cd /var/www/sipraja
```

Buat file `.env`:

```bash
cp .env.example .env
nano .env
```

Sesuaikan:

```env
APP_URL=https://domain-anda.com
DB_PASSWORD=password_database_yang_kuat
MYSQL_ROOT_PASSWORD=password_root_mysql_yang_kuat
```

## Jalankan Container Production

Build dan jalankan container:

```bash
docker compose up -d --build
```

Cek status container:

```bash
docker compose ps
```

Container yang harus berjalan:

```text
sipraja_app
sipraja_nginx
sipraja_mysql
sipraja_queue_exports
sipraja_queue_imports
sipraja_queue_default
```

Cek log apabila ada error:

```bash
docker compose logs -f app
docker compose logs -f nginx
docker compose logs -f mysql
```

## Setup Laravel Production

Install dependency Composer:

```bash
docker compose exec app composer install --no-dev --optimize-autoloader
```

Generate APP_KEY:

```bash
docker compose exec app php artisan key:generate
```

Migrasi database:

```bash
docker compose exec app php artisan migrate --force
```

Buat storage link:

```bash
docker compose exec app php artisan storage:link
```

Clear dan optimize cache:

```bash
docker compose exec app php artisan optimize:clear
docker compose exec app php artisan optimize
```

Restart queue worker:

```bash
docker compose exec app php artisan queue:restart
```

## Build Asset Frontend Production

Branch `docker-ready` tidak menjalankan Vite dev server. Asset frontend harus dibuild menjadi file production.

Jalankan di VPS:

```bash
docker run --rm -v "$PWD":/app -w /app node:22-alpine sh -lc "npm ci && npm run build"
```

Apabila tidak ada `package-lock.json`, gunakan:

```bash
docker run --rm -v "$PWD":/app -w /app node:22-alpine sh -lc "npm install && npm run build"
```

Setelah build asset:

```bash
docker compose exec app php artisan optimize:clear
docker compose exec app php artisan optimize
```

## Permission Folder

Pastikan folder Laravel yang perlu ditulis sudah memiliki permission benar:

```bash
docker compose exec app chmod -R 775 storage bootstrap/cache
```

Apabila masih muncul error permission:

```bash
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
```

## Akses Aplikasi

Apabila domain belum diarahkan:

```text
http://IP_VPS
```

Apabila domain sudah diarahkan:

```text
https://domain-anda.com
```

## Update Deployment Berikutnya

Di local:

```bash
git checkout docker-ready
git add .
git commit -m "Update production deployment"
git push origin docker-ready
```

Di VPS:

```bash
cd /var/www/sipraja
git pull origin docker-ready
docker compose up -d --build
docker compose exec app composer install --no-dev --optimize-autoloader
docker compose exec app php artisan migrate --force
docker compose exec app php artisan optimize:clear
docker compose exec app php artisan optimize
docker compose exec app php artisan queue:restart
```

Build ulang asset apabila ada perubahan file frontend:

```bash
docker run --rm -v "$PWD":/app -w /app node:22-alpine sh -lc "npm ci && npm run build"
docker compose exec app php artisan optimize:clear
docker compose exec app php artisan optimize
```

## Command Operasional

Cek container:

```bash
docker compose ps
```

Lihat semua log:

```bash
docker compose logs -f
```

Lihat log app:

```bash
docker compose logs -f app
```

Lihat log Nginx:

```bash
docker compose logs -f nginx
```

Lihat log MySQL:

```bash
docker compose logs -f mysql
```

Lihat log queue export:

```bash
docker compose logs -f queue_exports
```

Lihat log queue import:

```bash
docker compose logs -f queue_imports
```

Lihat log queue default:

```bash
docker compose logs -f queue_default
```

Masuk container app:

```bash
docker compose exec app bash
```

Jalankan artisan:

```bash
docker compose exec app php artisan migrate
docker compose exec app php artisan optimize:clear
docker compose exec app php artisan queue:restart
```

Stop container:

```bash
docker compose down
```

Jalankan ulang container:

```bash
docker compose up -d
```

Rebuild container:

```bash
docker compose up -d --build
```

## Backup Database

Backup database dari container MySQL:

```bash
docker compose exec mysql mysqldump -uroot -p sipraja > backup_sipraja.sql
```

Atau menggunakan password langsung dari shell VPS:

```bash
docker compose exec mysql sh -c 'mysqldump -uroot -p"$MYSQL_ROOT_PASSWORD" sipraja' > backup_sipraja.sql
```

## Restore Database

Restore dari file SQL:

```bash
docker compose exec -T mysql mysql -uroot -p sipraja < backup_sipraja.sql
```

Atau:

```bash
docker compose exec -T mysql sh -c 'mysql -uroot -p"$MYSQL_ROOT_PASSWORD" sipraja' < backup_sipraja.sql
```

## Troubleshooting

### 1. Access denied database

Error contoh:

```text
SQLSTATE[HY000] [1045] Access denied for user 'sipraja'
```

Penyebab umum:

* `DB_PASSWORD` di `.env` berbeda dengan password user MySQL yang sudah terbentuk di volume Docker.
* Volume MySQL sudah dibuat dengan password lama.
* `.env` berubah, tetapi volume database tidak dibuat ulang.

Solusi untuk VPS baru yang belum punya data penting:

```bash
docker compose down -v
docker compose up -d --build
```

Peringatan: `docker compose down -v` menghapus volume database.

Solusi tanpa menghapus database:

```bash
docker compose exec mysql mysql -uroot -p
```

Lalu jalankan:

```sql
ALTER USER 'sipraja'@'%' IDENTIFIED BY 'password_yang_sama_dengan_env';
GRANT ALL PRIVILEGES ON sipraja.* TO 'sipraja'@'%';
FLUSH PRIVILEGES;
```

### 2. Build Docker gagal karena Docker Hub

Error contoh:

```text
lookup auth.docker.io: no such host
```

Penyebab umum:

* DNS bermasalah.
* Docker Desktop belum bisa akses Docker Hub.
* VPN/proxy/firewall mengganggu koneksi.
* Belum login Docker.

Cek:

```bash
docker login
docker pull composer:2
docker pull php:8.3-fpm-bookworm
```

### 3. Queue tidak memproses export/import

Cek log:

```bash
docker compose logs -f queue_exports
docker compose logs -f queue_imports
```

Pastikan job diarahkan ke queue yang benar:

```php
ExportPendataanJob::dispatch($data)->onQueue('exports');
ImportPendataanJob::dispatch($data)->onQueue('imports');
```

Atau di dalam class Job:

```php
public $queue = 'exports';
```

Untuk import:

```php
public $queue = 'imports';
```

### 4. Asset CSS/JS tidak muncul

Build ulang asset:

```bash
docker run --rm -v "$PWD":/app -w /app node:22-alpine sh -lc "npm ci && npm run build"
```

Lalu:

```bash
docker compose exec app php artisan optimize:clear
docker compose exec app php artisan optimize
```

### 5. Laravel cache bermasalah

Jalankan:

```bash
docker compose exec app php artisan optimize:clear
docker compose exec app php artisan config:clear
docker compose exec app php artisan route:clear
docker compose exec app php artisan view:clear
docker compose exec app php artisan cache:clear
```

Lalu optimize ulang:

```bash
docker compose exec app php artisan optimize
```

## Catatan Shared Hosting

Branch `docker-ready` ini ditujukan untuk VPS dengan Docker.

Untuk shared hosting biasa, Docker umumnya tidak dijalankan di server. Docker hanya dipakai untuk build/test lokal, lalu project Laravel diupload dengan pola shared hosting biasa.

## Ringkasan Deploy Cepat VPS

```bash
cd /var/www
git clone -b docker-ready https://github.com/USERNAME/NAMA-REPOSITORY.git sipraja
cd sipraja
cp .env.example .env
nano .env
docker compose up -d --build
docker compose exec app composer install --no-dev --optimize-autoloader
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --force
docker compose exec app php artisan storage:link
docker run --rm -v "$PWD":/app -w /app node:22-alpine sh -lc "npm ci && npm run build"
docker compose exec app php artisan optimize:clear
docker compose exec app php artisan optimize
docker compose exec app php artisan queue:restart
```
