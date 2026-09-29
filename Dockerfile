FROM php:8.2-apache

# Extensiones PHP que Kirby necesita para procesar imágenes
# ImageMagick (binario `convert`) y no GD para los thumbs: GD descarta el
# perfil ICC al redimensionar y un sRGB perdido desatura las fotos en Safari.
# Ver site/config/config.php → thumbs.driver = 'im'.
RUN apt-get update -qq && \
    apt-get install -y -qq libgd-dev libzip-dev libjpeg62-turbo-dev libpng-dev \
                           libwebp-dev libfreetype6-dev imagemagick unzip git curl && \
    docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype && \
    docker-php-ext-install gd zip exif && \
    a2enmod rewrite && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html

# Kirby se instala desde el lock: build reproducible, no "lo que haya hoy en Packagist"
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-progress --optimize-autoloader

# Kirby no vive en vendor/: getkirby/composer-installer lo coloca en kirby/ y se
# guia por el campo `type: kirby-cms` de su entrada en composer.lock. Un lock mal
# construido, sin ese campo, instala sin dar error y el sitio muere en runtime
# con "Failed to open kirby/bootstrap.php". Que reviente el build, no produccion.
RUN if [ ! -f kirby/bootstrap.php ] || [ ! -d kirby/src/Cms ]; then \
      echo "ERROR: Kirby no se ha instalado en kirby/. Revisa el campo type de getkirby/cms en composer.lock."; \
      exit 1; \
    fi

# El sitio: nuestro código, versionado en este repo
COPY index.php .htaccess ./
COPY site/ ./site/
COPY assets/ ./assets/

# Contenido semilla: las cuatro páginas del sitio con sus textos. El
# initContainer del Deployment hace `cp -rn` de aquí a la PVC, así que planta
# lo que falte y nunca pisa lo que Nico haya editado en el panel.
# Ojo: esto NO borra el contenido demo que ya hay en la PVC. Ver docs/DESARROLLO.md.
COPY seed/content/ ./content/

# Migración a multiidioma: el initContainer la ejecuta sobre la PVC antes de sembrar.
COPY scripts/migrar-idiomas.sh /usr/local/bin/migrar-idiomas
RUN chmod +x /usr/local/bin/migrar-idiomas

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
