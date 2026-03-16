FROM php:8.2-fpm

# Install system packages
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    zlib1g-dev \
    libicu-dev \
    g++ \
    mysql-client \
    unzip \
    && docker-php-ext-install -j$(nproc) \
    gd \
    mysqli \
    pdo_mysql \
    bcmath \
    intl \
    && docker-php-ext-enable \
    gd \
    mysqli \
    pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# Create storage directories
RUN mkdir -p storage/logs && chmod -R 775 storage bootstrap/cache

# Copy environment file
RUN cp .env.example .env

# Generate application key
RUN php artisan key:generate

# Run migrations
RUN php artisan migrate --force

EXPOSE 9000

CMD ["php-fpm"]
