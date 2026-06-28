#!/bin/sh
set -e

echo "==> Installing Composer dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader

echo "==> Installing npm dependencies..."
npm install

echo "==> Building frontend assets..."
npm run build

# Create SQLite database file if it doesn't exist yet
[ -f database/database.sqlite ] || touch database/database.sqlite

echo "==> Running database migrations..."
php artisan migrate --force

echo "==> Linking storage..."
php artisan storage:link --force 2>/dev/null || true

echo "==> Init complete."
