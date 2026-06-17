FROM php:8.3-cli

WORKDIR /app

# Dépendances système
RUN apt-get update && apt-get install -y \
    git unzip zip curl \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    nodejs npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql zip mbstring gd

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copier le projet
COPY . .

# PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Frontend build (IMPORTANT mais déjà safe)
RUN npm install
RUN npm run build

# Cache Laravel
RUN php artisan config:cache
RUN php artisan route:cache || true
RUN php artisan view:cache || true

# Permissions
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 8080

CMD php artisan serve --host=0.0.0.0 --port=8080