# Inventory Management System - Laravel (P5)

Migrasi Inventory Management System P4 (PHP native) ke Laravel. Business rule P4 tetap dipertahankan.

## Kebutuhan
- PHP 8.2+ dan Composer
- MySQL / MariaDB (XAMPP)
- Laravel 11 atau 12

## Instalasi (dari nol)
```bash
# 1. Buat project Laravel baru
composer create-project laravel/laravel inventory-laravel
cd inventory-laravel

# 2. Salin isi paket ini ke dalam project (timpa file yang sudah ada)
#    Windows (CMD, dari dalam folder inventory-laravel):
#       xcopy /E /Y /I ..\inventory-laravel-overlay\app app
#       xcopy /E /Y /I ..\inventory-laravel-overlay\database database
#       xcopy /E /Y /I ..\inventory-laravel-overlay\resources resources
#       xcopy /E /Y /I ..\inventory-laravel-overlay\routes routes
#       xcopy /E /Y /I ..\inventory-laravel-overlay\public public
#    Atau cukup copy-paste manual folder app, database, resources, routes, public.

# 3. Atur database di file .env (ganti bagian DB_ yang bawaannya sqlite):
#       DB_CONNECTION=mysql
#       DB_HOST=127.0.0.1
#       DB_PORT=3306
#       DB_DATABASE=inventory_db
#       DB_USERNAME=root
#       DB_PASSWORD=
#    Lalu buat database kosong "inventory_db" di phpMyAdmin
#    (atau jalankan: CREATE DATABASE inventory_db;)

# 4. Migration + seeder
php artisan migrate:fresh --seed

# 5. Jalankan
php artisan serve