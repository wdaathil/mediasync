FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pgsql pdo_pgsql zip

COPY . /var/www/html/

RUN mkdir -p /var/www/html/uploads/images
RUN mkdir -p /var/www/html/uploads/videos

RUN chmod -R 777 /var/www/html/uploads

EXPOSE 80