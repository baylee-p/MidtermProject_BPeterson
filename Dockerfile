FFROM php:8.2-apache

# Enable mod_rewrite (optional)
RUN a2enmod rewrite

# Set working directory and copy project
WORKDIR /var/www/html

COPY . /var/www/html

# Change Apache DocumentRoot to point to /var/www/html/api
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/api|' /etc/apache2/sites-available/000-default.conf

# Expose port
EXPOSE 80
