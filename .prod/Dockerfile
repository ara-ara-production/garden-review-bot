# Билдер для node (vite + react build)
FROM node:20 AS node-builder

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY resources/ resources/
COPY vite.config.js ./
COPY public/ public/
RUN npm run build


# Билдер для Laravel
FROM php:8.4-fpm-alpine AS php-builder

RUN apk update && apk add \
        curl \
        autoconf \
        g++ \
        make \
        postgresql-dev \
        freetype-dev \
        libjpeg-turbo-dev \
        libwebp-dev \
        imagemagick-dev \
        imagemagick

# Установка и настройка расширений для php
RUN docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype
RUN docker-php-ext-install -j$(nproc) gd pdo pgsql pdo_pgsql redis

WORKDIR /var/www
COPY . ./

# Установка Composer под текущую версию php, затем установка зависимостей
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer install --optimize-autoloader --no-interaction --no-progress --prefer-dist

# Основной PHP + NGINX контейнер
FROM php:8.4-fpm-alpine

# Установка зависимостей
RUN apk add --no-cache \
    nginx \
    bash \
    curl \
    postgresql-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    imagemagick-dev \
    imagemagick \
    supervisor

ADD .prod/configs/php.ini /usr/local/etc/php/php.ini

# Копируем Laravel
COPY --from=php-builder /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=php-builder /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/
COPY --from=php-builder /usr/local/bin/docker-php-ext-* /usr/local/bin/
COPY --from=php-builder /var/www /var/www

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
 && chmod -R ug+rw /var/www/storage /var/www/bootstrap/cache


# Копируем собранные ассеты React
COPY --from=node-builder /app/public/build /var/www/public/build


# Копируем nginx конфиг
COPY .prod/configs/nginx.conf /etc/nginx/nginx.conf

# Supervisor конфиг
COPY .prod/configs/supervisord.conf /etc/supervisord.conf

# Устанавливаем рабочую директорию
WORKDIR /var/www

RUN  php artisan route:cache \
 && php artisan view:cache

# Порты и команда запуска
EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
