FROM php:8.4-cli

RUN apt-get update && apt-get upgrade -y && apt-get install -y \
    git curl zip unzip libpng-dev libjpeg-dev libfreetype6-dev libxml2-dev supervisor libonig-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath sockets gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install

COPY supervisord.conf /etc/supervisord.conf

CMD ["supervisord", "-c", "/etc/supervisord.conf"]
