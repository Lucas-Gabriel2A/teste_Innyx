# Dockerfile (innyx_back/Dockerfile)
FROM php:8.2-cli

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    zip unzip curl libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip


COPY --from=composer:2 /usr/bin/composer /usr/bin/composer


WORKDIR /var/www


COPY . .

RUN composer install

RUN chmod -R 777 storage bootstrap/cache
