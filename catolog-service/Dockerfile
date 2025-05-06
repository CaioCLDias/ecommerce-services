FROM php:8.4-cli-alpine

# Instala dependências
RUN apk add --no-cache \
    git curl zip unzip libpng-dev libxml2-dev libjpeg-turbo-dev freetype-dev oniguruma-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define diretório de trabalho
WORKDIR /var/www

# Copia o código da aplicação
COPY . .

# Instala dependências do PHP
RUN composer install

# Permissões
RUN chown -R www-data:www-data /var/www

# Executa migrations + inicia o servidor Laravel
CMD ["sh", "-c", "php artisan migrate --seed --force && php artisan serve --host=0.0.0.0 --port=8000"]
