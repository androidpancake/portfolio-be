#!/usr/bin/env bash
echo "Running composer"
composer global require hirak/prestissimo
composer install --no-dev --working-dir=/var/www/html

echo "generating application key..."
php artisan key:generate --show
php artisan key:generate

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Optimizing"
php artisan optimize

echo "Running migrations..."
php artisan migrate --force

echo "Add Admin Users"
php artisan db:seed --class=AdminSeeder
