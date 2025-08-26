#!/usr/bin/env bash
set -euo pipefail

echo "[entrypoint] Starting container bootstrap..."

# Ensure directories exist and permissions are correct
mkdir -p storage/framework/{cache,sessions,views} \
				 storage/{app,logs} \
				 bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

# Ensure log file exists
touch storage/logs/laravel.log || true
chown www-data:www-data storage/logs/laravel.log || true
chmod 664 storage/logs/laravel.log || true

# Install dependencies if vendor is missing or composer.lock changed
if [ ! -d vendor ] || [ ! -f vendor/autoload.php ]; then
	echo "[entrypoint] Installing composer dependencies..."
	composer install --no-interaction --prefer-dist --no-dev
fi

# Generate app key if missing
if ! grep -qE '^APP_KEY=base64:' .env 2>/dev/null; then
	echo "[entrypoint] Generating APP_KEY..."
	php artisan key:generate --force || true
fi

# Storage link
if [ ! -L public/storage ]; then
	php artisan storage:link || true
fi

# Optimize caches (ignore failures during first boot)
php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true
php artisan route:clear || true

# Run migrations if DB is available (best-effort)
if php -r 'exit((int)!(@fsockopen(getenv("DB_HOST")?:"db", (int)(getenv("DB_PORT")?:3306), $e, $s, 1)));'; then
	echo "[entrypoint] Running migrations..."
	php artisan migrate --force || true
else
	echo "[entrypoint] DB not reachable, skipping migrations for now."
fi

echo "[entrypoint] Bootstrap complete. Handing off to Apache..."

exec apache2-foreground

