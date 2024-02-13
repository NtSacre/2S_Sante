sleep 10
php artisan key:generate
php artisan migrate --force
php artisan optimize:clear
source seeder.sh
apache2-foreground