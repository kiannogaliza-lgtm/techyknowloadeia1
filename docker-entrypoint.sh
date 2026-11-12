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

# 4. Run Laravel migrations to set up schema
echo "Running migrations..."
php artisan migrate --force

# 5. Seed default accounts if users table is empty
echo "Seeding initial accounts if needed..."
php artisan db:seed --class=DatabaseSeeder --force || true
mariadb techyknowloadeia1 -e "
INSERT IGNORE INTO users (id, first_name, last_name, phone_number, email, role, status, password, created_at, updated_at) VALUES
(1, 'Resident', 'User', '09111111111', NULL, 'resident', 'active', '\$2y\$12\$yVeokU7cfLE4Ap8KPDYSiulbZPpBmnsKBXp58PSgyyww2lTDynFEG', NOW(), NOW()),
(2, 'BHW', 'User', '09222222222', NULL, 'bhw', 'active', '\$2y\$12\$yVeokU7cfLE4Ap8KPDYSiulbZPpBmnsKBXp58PSgyyww2lTDynFEG', NOW(), NOW()),
(3, 'Nurse', 'User', '09333333333', NULL, 'nurse', 'active', '\$2y\$12\$yVeokU7cfLE4Ap8KPDYSiulbZPpBmnsKBXp58PSgyyww2lTDynFEG', NOW(), NOW());
" || true

# Seed default medicines if medicines table is empty
mariadb techyknowloadeia1 -e "
INSERT IGNORE INTO medicines (id, name, category, stock, unit, reorder_level, created_at, updated_at) VALUES
(1, 'Paracetamol 500mg', 'Pain Reliever', 150, 'tablets', 50, NOW(), NOW()),
(2, 'Amoxicillin 500mg', 'Antibiotic', 80, 'capsules', 40, NOW(), NOW()),
(3, 'Vitamin C 500mg', 'Supplement', 25, 'tablets', 30, NOW(), NOW()),
(4, 'Ibuprofen 400mg', 'Pain Reliever', 95, 'tablets', 50, NOW(), NOW()),
(5, 'Cough Syrup', 'Cough Medicine', 15, 'bottles', 20, NOW(), NOW()),
(6, 'Antibiotic Ointment', 'Topical', 5, 'tubes', 15, NOW(), NOW());
" || true

# 6. Start Apache in foreground
echo "Starting Apache..."
exec apache2-foreground
