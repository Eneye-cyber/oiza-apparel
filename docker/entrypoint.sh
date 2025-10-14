#!/usr/bin/env bash
set -e

echo "Creating symbolic storage link..."
php artisan storage:link

echo "Running migrations..."
php artisan db:wipe --force

echo "Running migrations..."
php artisan migrate --force

echo "Running seeders..."
php artisan db:seed  --force

# echo "Caching config"
# php artisan config:cache

echo "Caching route"
php artisan route:cache

echo "Caching icons"
php artisan icons:cache

echo "Caching filament components"
php artisan filament:cache-components

echo "Caching view"
php artisan view:cache

echo "Caching event"
php artisan event:cache

echo "Starting supervisord..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
