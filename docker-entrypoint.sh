#!/bin/bash
set -euo pipefail

# Wait for the database to be ready
echo "Waiting for database..."
while ! nc -z db 3306; do
  sleep 1
done
echo "Database is ready."

export COMPOSER_ALLOW_SUPERUSER=1

# Fix git ownership issue (safe in container)
git config --global --add safe.directory /var/www/html || true

APP_ROOT=/var/www/html

# Create necessary directories if they don't exist
mkdir -p "$APP_ROOT/storage/logs" \
         "$APP_ROOT/storage/framework/cache" \
         "$APP_ROOT/storage/framework/sessions" \
         "$APP_ROOT/storage/framework/views" \
         "$APP_ROOT/storage/app/public" \
         "$APP_ROOT/bootstrap/cache"

# Ensure laravel.log exists
touch "$APP_ROOT/storage/logs/laravel.log"

# Set permissions (compatible with bind mounts)
chown -R www-data:www-data "$APP_ROOT/storage" "$APP_ROOT/bootstrap/cache" || true
chmod -R ug+rwX "$APP_ROOT/storage" "$APP_ROOT/bootstrap/cache" || true

# In dev, storage/logs can be strict on some hosts; relax to avoid 500s
chmod 777 "$APP_ROOT/storage/logs" || true

# Install dependencies first
if [ -f "$APP_ROOT/composer.json" ]; then
  composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev || composer install --no-interaction
fi

# Generate app key if missing
if [ -z "${APP_KEY:-}" ] || [ "$APP_KEY" = "" ]; then
  php artisan key:generate --force || true
fi

# Clear and rebuild caches safely (some may fail before env is complete)
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Run migrations (ignore if DB not ready for some reason)
php artisan migrate --force || true

# Optimize (optional in dev; keep lightweight)
php artisan config:cache || true
# Avoid route/view cache in dev to ease debugging

# Start Apache
exec apache2-foreground
