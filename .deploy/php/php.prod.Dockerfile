FROM php:8.4-fpm-alpine AS builder

RUN apk update && apk add \
        icu-libs \
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
RUN docker-php-ext-install -j$(nproc) gd pdo pgsql pdo_pgsql pcntl intl

WORKDIR /var/www
COPY . ./


# Установка Composer под текущую версию php, затем установка зависимостей
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer install --optimize-autoloader --no-interaction --no-progress --prefer-dist

FROM php:8.4-fpm-alpine

RUN apk update
RUN apk add \
        curl \
        postgresql-dev \
        freetype-dev \
        libjpeg-turbo-dev \
        libwebp-dev \
        imagemagick-dev \
        imagemagick

# Устанавливаем конфиг для php
ADD .deploy/php/php.prod.ini /usr/local/etc/php/php.ini

COPY --from=builder --chown=www-data:www-data /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=builder --chown=www-data:www-data /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/
COPY --from=builder --chown=www-data:www-data /usr/local/bin/docker-php-ext-* /usr/local/bin/

COPY --from=builder --chown=www-data:www-data /var/www /var/www

WORKDIR /var/www

## Устанавливаем владельцем папки пользователя www-data
#RUN chown -R www-data:www-data /var/www


RUN apk add --no-cache supervisor
COPY ./.deploy/php/supervisord.conf /etc/supervisord.conf


USER www-data

EXPOSE 9000
CMD ["supervisord", "-c", "/etc/supervisord.conf"]

