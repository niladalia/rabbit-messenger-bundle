FROM php:8.2-fpm

# Instalar dependencias y extensiones necesarias
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    zlib1g-dev \
    libxml2-dev \
    && docker-php-ext-install intl zip xml

# Instalar Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash

# Instalar Composer globalmente
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

# Copiar solo composer.json y composer.lock para optimizar las capas de cache de Docker
COPY composer.json /app/

# Instalar dependencias de Composer
RUN composer install --no-scripts --no-autoloader

# Copiar el resto de la aplicación
COPY . /app

# Ejecutar la instalación de Composer para autoloaders y scripts
RUN composer install

# Exponer el puerto 8000
EXPOSE 8000
