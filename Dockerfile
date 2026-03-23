FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libxml2-dev \
    libonig-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring xml gd tokenizer zip \
    && apt-get clean

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader \
    --ignore-platform-req=ext-gd \
    --ignore-platform-req=ext-zip

RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=$PORT
