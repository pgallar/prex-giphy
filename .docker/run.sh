#!/bin/sh

cd /var/www/html
php artisan migrate:fresh
php artisan db:seed
apache2-foreground
