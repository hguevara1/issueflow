FROM php:8.4-apache

ENV APP_ENV=prod \
    APP_DEBUG=0

RUN apt-get update \
    && apt-get install -y \
        git \
        unzip \
        libicu-dev \
        libzip-dev \
    && docker-php-ext-install \
        intl \
        zip \
        pdo_mysql \
    && rm -rf /var/lib/apt/lists/*
    
RUN a2enmod rewrite

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Directorio de trabajo dentro del contenedor
WORKDIR /var/www/html

# Copio el contenido del contexto de construcción al directorio de trabajo
COPY . .

# Instalo las dependencias de Composer
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

COPY docker/apache/000-default.conf \
    /etc/apache2/sites-available/000-default.conf

RUN mkdir -p var \
    && chown -R www-data:www-data var \
    && chmod -R 775 var