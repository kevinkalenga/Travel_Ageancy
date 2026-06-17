FROM php:8.3-cli

WORKDIR /app

# Dependencies
RUN apt-get update && apt-get install -y \
    git unzip zip curl \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libicu-dev \
    nodejs npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql zip mbstring gd intl

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Code
COPY . .

# Install backend
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Install frontend
RUN npm install
RUN npm run build

# Laravel cache (safe)
RUN php artisan config:clear
RUN php artisan cache:clear || true
RUN php artisan config:cache || true

# Permissions
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 8080

CMD php artisan serve --host=0.0.0.0 --port=8080