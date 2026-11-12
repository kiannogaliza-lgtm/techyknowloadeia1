#!/bin/bash
set -e

# 1. Setup folders and full permissions
mkdir -p /var/www/html/storage/logs \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/framework/cache
touch /var/www/html/storage/logs/laravel.log

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

# 2. Initialize and start MySQL (MariaDB)
mkdir -p /var/run/mysqld /var/lib/mysql
chown -R mysql:mysql /var/run/mysqld /var/lib/mysql

if [ ! -d "/var/lib/mysql/mysql" ]; then
    echo "Initializing MariaDB data directory..."
    mariadb-install-db --user=mysql --datadir=/var/lib/mysql > /dev/null 2>&1
fi

echo "Starting MariaDB service..."
service mariadb start

# Wait for MySQL to be ready
until mariadb-admin ping --silent; do
    echo "Waiting for MariaDB..."
    sleep 1
done

# 3. Create database and root user with empty password (matches local Laragon)
echo "Configuring database..."
mariadb -e "CREATE DATABASE IF NOT EXISTS \`techyknowloadeia1\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mariadb -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' IDENTIFIED VIA mysql_native_password USING PASSWORD('') WITH GRANT OPTION;"
mariadb -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'127.0.0.1' IDENTIFIED VIA mysql_native_password USING PASSWORD('') WITH GRANT OPTION;"
mariadb -e "FLUSH PRIVILEGES;"

# 4. Import seed database if medlink_database.sql exists
if [ -f "/var/www/html/medlink_database.sql" ]; then
    echo "Importing medlink_database.sql..."
    mariadb techyknowloadeia1 < /var/www/html/medlink_database.sql || true
fi

# 5. Run any remaining Laravel migrations
echo "Running migrations..."
php artisan migrate --force || true

# 6. Start Apache in foreground
echo "Starting Apache..."
exec apache2-foreground
