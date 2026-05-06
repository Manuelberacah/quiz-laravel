# =============================================================================
# Stage 1: Build — Install Composer dependencies
# =============================================================================
FROM php:8.2-fpm-alpine AS build

# Install system dependencies needed for building PHP extensions
RUN apk add --no-cache \
    bash \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    icu-dev \
    sqlite-dev

# Install required PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_sqlite \
        pdo_mysql \
        mbstring \
        gd \
        zip \
        bcmath \
        opcache \
        exif \
        fileinfo

# Install Composer from official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy only composer files first to leverage Docker layer caching
COPY composer.json composer.lock ./

# Install dependencies without dev packages, optimized for production
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --optimize-autoloader \
    --prefer-dist

# Copy the rest of the application source code
COPY . .

# =============================================================================
# Stage 2: Production — Lightweight final image
# =============================================================================
FROM php:8.2-fpm-alpine AS production

# Install only runtime system dependencies (no build tools)
RUN apk add --no-cache \
    nginx \
    supervisor \
    libzip \
    libpng \
    libjpeg-turbo \
    freetype \
    oniguruma \
    icu \
    sqlite \
    sqlite-libs \
    curl

# Copy PHP extensions from the build stage
COPY --from=build /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=build /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/

WORKDIR /app

# Copy the fully built application from the build stage
COPY --from=build /app /app

# ---------------------------------------------------------------------------
# Nginx Configuration
# ---------------------------------------------------------------------------
RUN mkdir -p /etc/nginx/conf.d
COPY docker/nginx.conf /etc/nginx/nginx.conf

# ---------------------------------------------------------------------------
# Supervisor Configuration (manages nginx + php-fpm processes)
# ---------------------------------------------------------------------------
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# ---------------------------------------------------------------------------
# PHP-FPM Configuration
# ---------------------------------------------------------------------------
COPY docker/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

# ---------------------------------------------------------------------------
# PHP Production Settings
# ---------------------------------------------------------------------------
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=10000" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/opcache.ini

# ---------------------------------------------------------------------------
# Entrypoint Script
# ---------------------------------------------------------------------------
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Set proper ownership for Laravel
RUN chown -R www-data:www-data /app \
    && chmod -R 755 /app/storage \
    && chmod -R 755 /app/bootstrap/cache

# Persistent volume for SQLite database and uploaded media
VOLUME ["/app/database", "/app/storage/app/public"]

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
