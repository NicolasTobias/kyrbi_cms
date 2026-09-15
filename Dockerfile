FROM php:8.2-apache

# Extensiones PHP que Kirby necesita para procesar imágenes
RUN apt-get update -qq && \
    apt-get install -y -qq libgd-dev libzip-dev libjpeg62-turbo-dev libpng-dev \
                           libwebp-dev libfreetype6-dev unzip git curl && \
    docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype && \
    docker-php-ext-install gd zip exif && \
    a2enmod rewrite && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html

# Kirby se instala desde el lock: build reproducible, no "lo que haya hoy en Packagist"
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-progress --optimize-autoloader

# El sitio: nuestro código, versionado en este repo
COPY index.php .htaccess ./
COPY site/ ./site/
COPY assets/ ./assets/

# Cloudflare termina TLS; Kirby tiene que enterarse de que la petición era HTTPS
RUN echo 'SetEnvIf X-Forwarded-Proto "https" HTTPS=on' > /etc/apache2/conf-enabled/force-https.conf && \
    printf '%s\n' '<?php' \
      '// Solo si el proxy dice que la peticion original era HTTPS.' \
      '// Incondicional rompe el desarrollo local sobre HTTP.' \
      "if ((\$_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https') {" \
      "    \$_SERVER['HTTPS'] = 'on';" \
      '}' > /usr/local/etc/php/prepend.php && \
    echo 'auto_prepend_file = /usr/local/etc/php/prepend.php' > /usr/local/etc/php/conf.d/prepend.ini

# Directorios de estado: existen en la imagen, pero en el clúster los tapa la PVC
RUN mkdir -p site/accounts site/cache site/sessions content && \
    chown -R www-data:www-data /var/www/html

EXPOSE 80
