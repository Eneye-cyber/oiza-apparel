#!/usr/bin/env bash
set -e

echo "Running composer"
composer install --no-dev --working-dir=/var/www/html --prefer-dist --optimize-autoloader

echo "Installing Node dependencies"
cd /var/www/html
npm ci --omit=dev   # faster and clean install

echo "Building frontend assets"
npm run build

echo "Creating symbolic storage link..."
php artisan storage:link || true

echo "Resetting and running migrations..."
php artisan migrate:fresh --seed --force   # Wipes, migrates, and seeds in one

echo "Caching config"
php artisan config:cache

echo "Caching route"
php artisan route:cache

echo "Caching view"
php artisan view:cache

echo "Caching event"
php artisan event:cache

echo "✅ Deployment complete!"
