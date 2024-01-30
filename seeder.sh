#!/bin/bash

# Script pour exécuter les seeders
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=HopitalSeeder
php artisan db:seed --class=VilleSeeder
php artisan db:seed --class=SecteurActiviteSeeder
php artisan db:seed 


