# syntax=docker/dockerfile:1.7-labs

########## Stage 1: Vendor di PHP 8.2 ##########
FROM php:8.2-cli-bookworm AS vendor
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /app

# pasang installer lalu install gd
RUN apt-get update && apt-get install -y curl git unzip \
    && curl -fsSL https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions \
    -o /usr/local/bin/install-php-extensions \
    && chmod +x /usr/local/bin/install-php-extensions \
    && install-php-extensions gd zip

COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --no-scripts

########## Stage 2: Frontend build (Vite) ##########
FROM node:20-alpine AS assets
WORKDIR /app
# salin hanya file yang dibutuhkan agar layer cache efektif
COPY package.json package-lock.json* pnpm-lock.yaml* yarn.lock* ./
# gunakan salah satu package manager; contoh npm:
RUN npm ci
# salin source untuk build
COPY --from=vendor /app/vendor/masmerise/livewire-toaster/resources/js ./vendor/masmerise/livewire-toaster/resources/js
COPY --from=vendor /app/vendor/livewire/livewire/dist ./vendor/livewire/livewire/dist
COPY resources ./resources
COPY vite.config.* ./
COPY postcss.config.* ./
COPY tailwind.config.* ./
# jika ada file tambahan yang dipakai saat build (mis. tsconfig):
COPY tsconfig.* ./
# jalankan build Vite
RUN npm run build

########## Stage 3: App final (FrankenPHP) ##########
FROM dunglas/frankenphp:1-php8.2-bookworm

# Ekstensi PHP umum untuk Laravel
RUN install-php-extensions \
    pdo_pgsql pgsql intl bcmath pcntl opcache gd zip exif redis

WORKDIR /app

# 1) vendor dari stage composer
COPY --from=vendor /app/vendor ./vendor

# 2) salin source aplikasi (kecuali node_modules, dsb — atur di .dockerignore)
COPY . .

RUN rm -f bootstrap/cache/*.php \
    && mkdir -p bootstrap/cache \
    && mkdir -p storage/framework/cache \
    storage/framework/data \
    storage/framework/sessions \
    storage/framework/views \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rw storage bootstrap/cache

# 3) hasil build frontend ke public/build (default Vite)
COPY --from=assets /app/public/build ./public/build

# Konfigurasi FrankenPHP/Caddy
ENV SERVER_NAME=:8080
ENV DOCUMENT_ROOT=/app/public

EXPOSE 8080

# (Opsional) Healthcheck — buat route /health yang return 200
# HEALTHCHECK --interval=30s --timeout=3s \
#   CMD curl -f http://localhost:8080/health || exit 1
