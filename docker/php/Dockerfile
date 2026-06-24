FROM php:8.3-fpm-bookworm

WORKDIR /var/www/html

ENV COMPOSER_ALLOW_SUPERUSER=1 \
    TZ=Asia/Makassar

# Catatan:
# - Project SI PRAJA memakai Laravel 11 + Maatwebsite Excel + Yajra DataTables.
# - Redis tidak dipasang karena .env project memakai database queue/cache/session.
# - Ext XML bawaan PHP official image umumnya sudah aktif; yang wajib ditambah: pdo_mysql, gd, intl, zip, bcmath, pcntl.
RUN apt-get update && apt-get install -y --no-install-recommends \
    bash \
    ca-certificates \
    curl \
    git \
    unzip \
    zip \
    libcurl4-openssl-dev \
    libfreetype6-dev \
    libicu-dev \
    libjpeg62-turbo-dev \
    libonig-dev \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    default-mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        bcmath \
        exif \
        gd \
        intl \
        mbstring \
        opcache \
        pcntl \
        pdo_mysql \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/pear

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-sipraja.ini
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/99-opcache.ini

CMD ["php-fpm"]
