FROM php:8.2-apache

# Enable Apache modules
RUN a2enmod rewrite

# Install PostgreSQL PDO driver
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Set web root to api folder
WORKDIR /var/www/html
COPY . /var/www/html

# Set Apache DocumentRoot
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/api|' /etc/apache2/sites-available/000-default.conf

# Configure directory access and index file
RUN echo '<Directory "/var/www/html/api">
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>

<IfModule mod_dir.c>
    DirectoryIndex index.php index.html
</IfModule>' > /etc/apache2/conf-available/api.conf && a2enconf api

EXPOSE 80
