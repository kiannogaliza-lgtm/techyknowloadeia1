#!/bin/bash
set -e

# Ensure SQLite file exists and has proper permissions
touch /var/www/html/database/database.sqlite
chown -R www-data:www-data /var/www/html/database /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/database /var/www/html/storage /var/www/html/bootstrap/cache

# Run Laravel migrations automatically in production
php artisan migrate --force || true

# Start Apache in the foreground
exec apache2-foreground
