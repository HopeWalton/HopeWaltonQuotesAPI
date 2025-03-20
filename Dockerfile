# Use an official PHP-Apache image
FROM php:8.2-apache

# Install required PHP extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html/

# Add a test file for debugging
RUN echo "Render Deployment Successful" > /var/www/html/deployment_check.txt

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Expose the correct port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
