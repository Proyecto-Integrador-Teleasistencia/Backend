FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    zip unzip git curl libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer (última versión)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Definir el directorio de trabajo
WORKDIR /var/www/html

# Establecer permisos correctos
RUN chown -R www-data:www-data /var/www/html

# Exponer el puerto de PHP-FPM (Opcional)
EXPOSE 9000

# Comando por defecto al iniciar el contenedor
CMD ["php-fpm"]