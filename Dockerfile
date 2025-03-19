# Use official PHP Apache image
FROM php:8.2-apache

# Enable mod_rewrite for Apache
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy all files into the container
COPY . /var/www/html/

# Ensure Apache serves index.php
RUN echo "<IfModule mod_dir.c>\n    DirectoryIndex index.php index.html\n</IfModule>" \
    > /etc/apache2/mods-enabled/dir.conf

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose port 80
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
