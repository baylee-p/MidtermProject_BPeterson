FROM php:8.2-apache

# Enable Apache rewrite
RUN a2enmod rewrite

# Install PostgreSQL PDO driver
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Set working directory and copy files
WORKDIR /var/www/html
COPY . /var/www/html

# Set DocumentRoot to /api
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/api|' /etc/apache2/sites-available/000-default.conf

# Copy Apache config and enable it
COPY apache.conf /etc/apache2/conf-available/api.conf
RUN a2enconf api

EXPOSE 80
