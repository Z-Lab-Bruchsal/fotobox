FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
        git curl zip unzip \
        libsqlite3-dev libpng-dev libzip-dev libonig-dev libexif-dev \
        gmic \
    && docker-php-ext-install pdo_sqlite gd zip mbstring bcmath pcntl exif \
    && rm -rf /var/lib/apt/lists/*

# Node.js 22
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
