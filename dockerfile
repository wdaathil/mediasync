FROM php:8.2-apache

# Install PostgreSQL dependencies first
RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip \
    unzip \
    && docker-php-ext-install pgsql pdo_pgsql

# Copy project files
COPY . /var/www/html/

# Create upload folders
RUN mkdir -p /var/www/html/uploads/images
RUN mkdir -p /var/www/html/uploads/videos

# Set permissions
RUN chmod -R 777 /var/www/html/uploads

EXPOSE 80