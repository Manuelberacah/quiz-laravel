#!/bin/sh
set -e

echo "==> Starting Laravel Quiz System..."

# Ensure the SQLite database file exists (on the mounted persistent disk)
if [ ! -f "$DB_DATABASE" ]; then
    echo "==> Creating SQLite database at $DB_DATABASE"
    mkdir -p "$(dirname "$DB_DATABASE")"
    touch "$DB_DATABASE"
fi

# Ensure storage directories exist and are writable
mkdir -p /app/storage/app/public
mkdir -p /app/storage/framework/cache
mkdir -p /app/storage/framework/sessions
mkdir -p /app/storage/framework/views
mkdir -p /app/storage/logs
mkdir -p /app/bootstrap/cache

chown -R www-data:www-data /app/storage /app/bootstrap/cache
chmod -R 775 /app/storage /app/bootstrap/cache

# Create the storage symlink if it doesn't exist
if [ ! -L /app/public/storage ]; then
    echo "==> Creating storage symlink..."
    php artisan storage:link
fi

# Run database migrations automatically on every deploy
echo "==> Running database migrations..."
php artisan migrate --force

# Cache Laravel configuration for performance
echo "==> Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Laravel is ready. Starting services..."

# Hand off to supervisor (starts nginx + php-fpm)
exec "$@"
