FROM php:8.3-cli

WORKDIR /app

# =========================
# System dependencies
# =========================
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libicu-dev \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        zip \
        mbstring \
        gd \
        intl

# =========================
# Composer
# =========================
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# =========================
# Copy project
# =========================
COPY . .

# =========================
# Backend dependencies
# =========================
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --optimize-autoloader

# =========================
# Frontend build (Vite)
# =========================
RUN npm install
RUN npm run build

# =========================
# Laravel optimization
# =========================
RUN php artisan config:clear || true
RUN php artisan route:cache || true
RUN php artisan view:cache || true
RUN php artisan config:cache || true

# =========================
# Permissions
# =========================
RUN chmod -R 775 storage bootstrap/cache

# =========================
# Expose port Railway
# =========================
EXPOSE 8080

# =========================
# Start server
# =========================
CMD php artisan serve --host=0.0.0.0 --port=8080