#!/bin/bash
set -e

# Ensure storage/logs and SQLite file exist with full permissions
mkdir -p /var/www/html/storage/logs /var/www/html/storage/framework/sessions /var/www/html/storage/framework/views /var/www/html/storage/framework/cache
touch /var/www/html/storage/logs/laravel.log
touch /var/www/html/database/database.sqlite

chown -R www-data:www-data /var/www/html/database /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 777 /var/www/html/database /var/www/html/storage /var/www/html/bootstrap/cache

# Run Laravel migrations automatically in production
php artisan migrate --force || true

# Start Apache in the foreground
exec apache2-foreground
