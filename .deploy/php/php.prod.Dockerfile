# syntax=docker/dockerfile:1.7

########################
# STAGE: php ext build #
########################
FROM php:8.4-fpm-alpine AS php_exts

# Только разовая установка инструментов для билда
RUN apk add --no-cache autoconf g++ make \
    icu-dev libzip-dev zlib-dev postgresql-dev \
    freetype-dev libjpeg-turbo-dev libwebp-dev \
    imagemagick-dev curl unzip zip

# Сборка расширений
RUN docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype \
 && docker-php-ext-configure zip \
 && docker-php-ext-install -j"$(nproc)" gd zip pdo pgsql pdo_pgsql pcntl intl opcache

# (опционально, если нужен imagick для PHP)
# RUN pecl install imagick && docker-php-ext-enable imagick

########################
# STAGE: composer deps #
########################
FROM composer:2 AS composer_deps
WORKDIR /app

# Копируем только манифесты — кэш не будет инвалидироваться из-за кода
COPY composer.json composer.lock ./

# Кэш Composer (BuildKit)
RUN --mount=type=cache,target=/tmp/composer \
    composer install --no-dev --prefer-dist --no-interaction --no-progress

#######################
# STAGE: assets (opt) #
#######################
# Если у тебя есть фронтенд-сборка — добавь stage с node и вынеси артефакты:
# FROM node:20 AS assets
# WORKDIR /app
# COPY package*.json ./
# RUN --mount=type=cache,target=/root/.npm npm ci
# COPY resources/ resources/
# RUN npm run build

################
# RUNTIME      #
################
FROM php:8.4-fpm-alpine

# Только рантайм-библиотеки (без -dev)
RUN apk add --no-cache icu-libs libzip zlib postgresql-libs \
    freetype libjpeg-turbo libwebp imagemagick curl supervisor

# php.ini
ADD .deploy/php/php.prod.ini /usr/local/etc/php/php.ini

# Копируем собранные расширения и конфиги
COPY --from=php_exts /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=php_exts /usr/local/etc/php/conf.d/     /usr/local/etc/php/conf.d/

# Vendor из composer stage
WORKDIR /var/www
COPY --from=composer_deps /app/vendor ./vendor

# Теперь уже исходники (после vendor — кэш слоёв дольше живёт)
# Благодаря .dockerignore сюда НЕ попадут лишние данные
COPY --link . .

# (если есть фронтовые артефакты)
# COPY --from=assets /app/public/build ./public/build

# Прегенерация кешей (если у тебя есть artisan)
# RUN php artisan config:cache && php artisan route:cache

# Supervisor
COPY ./.deploy/php/supervisord.conf /etc/supervisord.conf

# Права (если нужно)
# RUN chown -R www-data:www-data /var/www

USER www-data
EXPOSE 9000
CMD ["supervisord", "-c", "/etc/supervisord.conf"]
