#!/usr/bin/env sh
set -e

cd /var/www/html

# Folder yang harus bisa ditulis Laravel, queue, import/export, dan upload publik.
mkdir -p \
    storage/app/private \
    storage/app/public \
    storage/app/templates/documents \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    public/uploads

# Tetap sediakan public/storage untuk kompatibilitas Laravel,
# tetapi Nginx juga sudah punya alias /storage sehingga symlink bukan titik kegagalan utama.
if [ ! -e public/storage ]; then
    ln -s ../storage/app/public public/storage 2>/dev/null || true
fi

# Pada bind mount, owner sering berbeda. Bagian ini mencegah error Permission denied
# saat upload ke public/uploads, cache, session, log, import, dan export.
chown -R www-data:www-data storage bootstrap/cache public/uploads 2>/dev/null || true
chmod -R ug+rwX storage bootstrap/cache public/uploads 2>/dev/null || true

exec "$@"
