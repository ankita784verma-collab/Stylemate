FROM php:8.2-apache

# Install and enable the mysqli extension required by db.php
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Copy application source code to the Apache web server root
COPY . /var/www/html/

# Ensure the uploads directory exists and is writable by the Apache process
RUN mkdir -p /var/www/html/uploads && chmod -R 777 /var/www/html/uploads

# Enable Apache mod_rewrite (if needed for routing)
RUN a2enmod rewrite

# Expose HTTP port
EXPOSE 80
