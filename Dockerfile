FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    default-mysql-client \
    g++ \
    libfreetype6-dev \
    libicu-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev \
    unzip \
    zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        gd \
        intl \
        mysqli \
        pdo_mysql \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-scripts

COPY . .
COPY docker/app/entrypoint.sh /usr/local/bin/ql-su-kien-entrypoint

RUN chmod +x /usr/local/bin/ql-su-kien-entrypoint \
    && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chmod -R ug+rw storage bootstrap/cache \
    && composer dump-autoload --optimize --no-scripts

EXPOSE 9000

ENTRYPOINT ["ql-su-kien-entrypoint"]
CMD ["php-fpm"]
