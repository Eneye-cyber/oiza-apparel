#!/usr/bin/env bash
set -e

echo "Running migrations and seeders..."
php artisan db:wipe --force
php artisan migrate --force
php artisan db:seed --force

echo "Starting supervisord..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
