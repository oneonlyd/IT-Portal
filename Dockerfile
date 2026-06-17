FROM php:8.2-apache

# Install extension PHP yang umum dipakai
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable mod_rewrite jika diperlukan
RUN a2enmod rewrite

# Copy source code
COPY . /var/www/html/

# Set permission
RUN chown -R www-data:www-data /var/www/html