#!/bin/bash
sed -i '/DB_DATABASE=S2S_v2/d' .env
echo "DB_DATABASE=S2S_v2_test" >> .env
# Script pour exécuter les seeders
php artisan optimize:clear

# Exécuter migrate:fresh uniquement si la vérification précédente réussit
php artisan migrate:fresh
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=HopitalSeeder
php artisan db:seed --class=VilleSeeder
php artisan db:seed --class=SecteurActiviteSeeder
php artisan db:seed

php artisan test

sed -i '/DB_DATABASE=S2S_v2_test/d' .env
echo "DB_DATABASE=S2S_v2" >> .env