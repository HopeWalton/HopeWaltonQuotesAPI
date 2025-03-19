# Use official PHP with Apache
FROM php:8.2-apache

# Enable Apache mod_rewrite for clean URLs
RUN a2enmod rewrite

# Set the working directory in the container
WORKDIR /var/www/html

# Copy project files into the container
COPY . /var/www/html

# Install required PHP extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose port 80 for Apache
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
