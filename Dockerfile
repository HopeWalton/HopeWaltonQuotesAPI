# Use an official PHP-Apache image
FROM php:8.2-apache

# Install required PHP extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql

# Set the working directory
WORKDIR /var/www/html

# Copy project files into the container
COPY . /var/www/html/

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Expose the correct port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
