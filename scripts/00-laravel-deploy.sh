#!/usr/bin/env bash
echo "Running composer"
# composer global require hirak/prestissimo
# composer install --no-dev --working-dir=/var/www/html
composer install --no-dev --working-dir=/var/www/html --optimize-autoloader --no-interaction

echo "Creating symbolic storage link..."
php artisan storage:link

echo "Running migrations..."
php artisan db:wipe --force

echo "Running migrations..."
php artisan migrate --force

echo "Running seeders..."
php artisan db:seed  --force

echo "Caching config"
php artisan config:cache

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


