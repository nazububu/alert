FROM php:8.1-fpm

RUN apt-get update && apt-get install -y git curl libpng-dev libonig-dev libxml2-dev zip unzip libssl-dev

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

RUN pecl install xdebug && docker-php-ext-enable xdebug
COPY ini/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN chmod 777 /var --recursive

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
