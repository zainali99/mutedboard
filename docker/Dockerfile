FROM php:8.2-fpm

# Install common PHP extensions
RUN apt-get update \
    && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev libzip-dev libonig-dev zip unzip git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd mbstring zip opcache

# Set working directory
WORKDIR /var/www/html
