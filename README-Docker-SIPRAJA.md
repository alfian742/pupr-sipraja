# Konfigurasi Docker SI PRAJA - Mode Pengalihan hPanel ke IP

Paket ini sudah disesuaikan dengan kondisi pada gambar hPanel:

```text
https://sipraja.lomboktengahkab.go.id/  ->  http://103.168.246.50
```

Karena hPanel melakukan **redirect ke IP**, browser pengguna akan membuka aplikasi sebagai:

```text
http://103.168.246.50
```

Artinya Docker harus melayani aplikasi langsung pada **port 80 publik server**, bukan hanya `127.0.0.1:8080`.

## Skema Deployment Sekarang

```text
User / Browser
  ↓
https://sipraja.lomboktengahkab.go.id
  ↓ redirect hPanel
http://103.168.246.50
  ↓ port 80 server
Nginx Docker SI PRAJA
  ↓
PHP-FPM Laravel + MySQL Docker
```

## Perubahan Penting

1. `docker-compose.yml`
   - Nginx Docker dipublish ke `0.0.0.0:80:80` agar bisa diakses melalui `http://103.168.246.50`.
   - Folder `public/uploads` dan `storage/app/public` dibuat sebagai volume eksplisit agar file upload tetap aman dan persisten.

2. `docker/nginx/default.conf`
   - `server_name` mendukung `103.168.246.50`, `sipraja.lomboktengahkab.go.id`, dan fallback `_`.
   - `/uploads` langsung disajikan dari `public/uploads`.
   - `/storage` langsung disajikan dari `storage/app/public`, sehingga tetap berjalan walaupun `php artisan storage:link` gagal.
   - Eksekusi PHP dari `/uploads` dan `/storage` diblokir.
   - Batas upload dinaikkan ke `150M`.

3. `docker/php/entrypoint.sh`
   - Otomatis membuat folder writable Laravel.
   - Otomatis membuat `public/uploads`.
   - Otomatis mencoba membuat `public/storage`.
   - Otomatis memperbaiki permission `storage`, `bootstrap/cache`, dan `public/uploads`.

4. `config/filesystems.php`
   - Disk `public` tetap memakai `storage/app/public` dan URL `/storage`.
   - Disk baru `uploads` ditambahkan untuk penyimpanan langsung ke `public/uploads`.

## Isi `.env` yang Wajib Disesuaikan

Karena akses akhirnya melalui IP, pastikan `.env` server memakai nilai berikut:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=http://103.168.246.50
SESSION_DOMAIN=
FILESYSTEM_DISK=public
```

Bagian database tetap sesuaikan dengan password asli:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=sipraja
DB_USERNAME=sipraja
DB_PASSWORD=password_database_asli
MYSQL_ROOT_PASSWORD=password_root_mysql_asli
```

## Cara Jalankan Ulang dari Server

Masuk ke folder project:

```bash
cd /path/ke/project/sipraja
```

Salin `.env.example` jika belum ada `.env`:

```bash
cp .env.example .env
nano .env
```

Buat folder upload dan storage publik di host:

```bash
mkdir -p public/uploads storage/app/public storage/logs bootstrap/cache
```

Build dan jalankan container:

```bash
docker compose down
docker compose up -d --build
```

Install dependency Laravel jika belum pernah:

```bash
docker compose exec app composer install --no-dev --optimize-autoloader
```

Generate key jika `APP_KEY` masih kosong:

```bash
docker compose exec app php artisan key:generate --force
```

Migrasi database:

```bash
docker compose exec app php artisan migrate --force
```

Buat storage link. Jika gagal, aplikasi tetap bisa membuka `/storage` karena Nginx sudah memakai alias langsung:

```bash
docker compose exec app php artisan storage:link
```

Build asset frontend:

```bash
docker run --rm -v "$PWD":/app -w /app node:22-alpine sh -lc "npm ci && npm run build"
```

Clear dan optimize:

```bash
docker compose exec app php artisan optimize:clear
docker compose exec app php artisan optimize
docker compose exec app php artisan queue:restart
```

## Tes Akses

Cek container:

```bash
docker compose ps
```

Tes dari server:

```bash
curl -I http://127.0.0.1
curl -I http://103.168.246.50
```

Tes file upload langsung ke public:

```bash
mkdir -p public/uploads/test
echo OK > public/uploads/test/cek.txt
curl http://127.0.0.1/uploads/test/cek.txt
```

Tes storage public:

```bash
mkdir -p storage/app/public/test
echo OK > storage/app/public/test/cek.txt
curl http://127.0.0.1/storage/test/cek.txt
```

Lihat log:

```bash
docker compose logs -f nginx
docker compose logs -f app
```

## Catatan Jika Port 80 Sudah Dipakai

Konfigurasi ini membutuhkan port 80 server. Jika muncul error seperti `bind: address already in use`, berarti ada service lain di server yang sudah memakai port 80, misalnya Apache/Nginx host.

Cek service pemakai port 80:

```bash
sudo ss -ltnp | grep ':80'
```

Jika memang aplikasi harus mengikuti pengalihan hPanel ke `http://103.168.246.50`, maka port 80 harus diarahkan ke Docker SI PRAJA. Pilih salah satu:

1. Matikan service web host yang memakai port 80, lalu jalankan Docker; atau
2. Admin server membuat reverse proxy host dari port 80 ke Nginx Docker; atau
3. Admin hPanel mengubah pengalihan ke IP dan port yang benar, misalnya `http://103.168.246.50:8080`, jika Docker tetap memakai port 8080.

Untuk kondisi pada gambar, pilihan yang paling sesuai adalah Docker melayani port 80 publik.
