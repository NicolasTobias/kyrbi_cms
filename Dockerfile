FROM php:8.2-apache

# Instalar extensiones PHP requeridas por Kirby
RUN apt-get update -qq && \
    apt-get install -y -qq libgd-dev libzip-dev unzip git curl && \
    docker-php-ext-install gd zip && \
    a2enmod rewrite && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar Kirby Starterkit
RUN composer create-project getkirby/starterkit /tmp/kirby --no-interaction --no-dev && \
    cp -a /tmp/kirby/. /var/www/html/ && \
    rm -rf /tmp/kirby

# Permisos para Apache
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
