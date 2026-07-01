#!/bin/sh
set -e

if [ "$(whoami)" != "root" ]; then
    SUDO=sudo
fi

${SUDO} apt-get update
${SUDO} apt-get -y install lsb-release ca-certificates curl
${SUDO} curl -sSLo /tmp/debsuryorg-archive-keyring.deb https://packages.sury.org/debsuryorg-archive-keyring.deb
${SUDO} dpkg -i /tmp/debsuryorg-archive-keyring.deb
${SUDO} sh -c 'echo "deb [signed-by=/usr/share/keyrings/debsuryorg-archive-keyring.gpg] https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list'
${SUDO} apt-get update

apt-get update
apt-get -y install php8.5 php8.5-cli php8.5-zip php8.5-sqlite3 php8.5-xml php8.5-pgsql php8.5-bcmath php8.5-intl php8.5-mbstring sqlite3 composer npm
curl -fsSL -o gmic.deb "https://gmic.eu/get_file.php?file=linux/gmic_3.7.6_debian13_trixie_amd64.deb"
apt-get -y install ./gmic.deb
rm gmic.deb

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
