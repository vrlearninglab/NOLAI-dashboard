# Gebruik een officiële PHP-Apache image
FROM php:8.2-apache

# Installeer benodigde PHP-extensies en tools
RUN docker-php-ext-install pdo pdo_mysql
RUN apt-get update && apt-get install -y git unzip

# Schakel Apache rewrite in
RUN a2enmod rewrite

# Kopieer de Laravel-bestanden naar de container, maar negeer de symlink
WORKDIR /var/www/html
COPY --chown=www-data:www-data . .


# Installeer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installeer PHP-afhankelijkheden
RUN composer install --no-dev --optimize-autoloader

# Maak de storage-map en stel permissies in
RUN mkdir -p storage/app/public
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

# Maak de symlink voor public/storage in de container
RUN php artisan storage:link

# Stel de document root in op 'public'
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf
