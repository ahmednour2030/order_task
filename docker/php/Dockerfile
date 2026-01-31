FROM php:8.2-fpm

WORKDIR /var/www/html

# 1. Added 'default-mysql-client' to help PHP communicate with MySQL
# 2. Re-ordered commands for better build caching
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    libpq-dev \
    default-mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql \
        pdo_pgsql \
        pgsql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        zip \
        intl \
        gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 3. CRITICAL: Copy your code BEFORE setting permissions
# If you don't copy the files first, the chown command has nothing to fix
COPY . /var/www/html

# 4. Set permissions correctly for Laravel 12
# git ownership
RUN git config --system --add safe.directory /var/www/html

RUN mkdir -p storage bootstrap/cache \
    && mkdir -p /var/www/html/vendor \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

USER www-data

EXPOSE 9000
CMD ["php-fpm"]
