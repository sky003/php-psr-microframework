FROM php:7.1-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    openssl \
    git \
    unzip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install pdo pdo_pgsql

WORKDIR /var/www/app