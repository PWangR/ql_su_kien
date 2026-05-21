#!/usr/bin/env sh
set -e

mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chmod -R ug+rw storage bootstrap/cache 2>/dev/null || true

if [ ! -f .env ] && [ -f .env.docker.example ]; then
    cp .env.docker.example .env
fi

if [ -f artisan ]; then
    php artisan config:clear >/dev/null 2>&1 || true

    if [ -f .env ] && grep -q '^APP_KEY=$' .env; then
        php artisan key:generate --force >/dev/null
    fi
fi

exec "$@"
