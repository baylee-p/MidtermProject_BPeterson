FROM php:8.2-apache

RUN a2enmod rewrite

# Set working dir and copy
WORKDIR /var/www/html

COPY . /var/www/html

# Set the DocumentRoot to /var/www/html/api
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/api|' /etc/apache2/sites-available/000-default.conf

# Set DirectoryIndex to use index.php
RUN echo "<IfModule mod_dir.c>\n    DirectoryIndex index.php index.html\n</IfModule>" > /etc/apache2/conf-available/dir.conf \
    && a2enconf dir

EXPOSE 80
