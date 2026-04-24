#!/bin/bash
echo "Running migrations..."
php artisan migrate --seed --force
echo "Migrations done!"
echo "Starting FrankenPHP..."
frankenphp run --config Caddyfile
