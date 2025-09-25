FROM php:8.4-fpm-alpine AS builder

# system deps for building extensions
RUN apk update && apk add --no-cache \
    # build toolchain
    autoconf g++ make \
    # libs for extensions
    icu-dev \
    libzip-dev zlib-dev \
    postgresql-dev \
    freetype-dev libjpeg-turbo-dev libwebp-dev \
    imagemagick-dev \
    # helpers
    curl unzip zip

# PHP extensions
RUN docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype \
 && docker-php-ext-configure zip \
 && docker-php-ext-install -j"$(nproc)" \
      gd zip pdo pgsql pdo_pgsql pcntl intl

WORKDIR /var/www
COPY . ./

# Composer + deps install
RUN curl -sS https://getcomposer.org/installer | php -- \
      --install-dir=/usr/local/bin --filename=composer \
 && composer install --optimize-autoloader --no-interaction --no-progress --prefer-dist
# если это прод — можно добавить:  --no-dev

# ---------------- RUNTIME ----------------
FROM php:8.4-fpm-alpine

# только runtime-библиотеки (без *-dev)
RUN apk update && apk add --no-cache \
    icu-libs \
    libzip zlib \
    postgresql-libs \
    freetype libjpeg-turbo libwebp \
    imagemagick \
    curl supervisor

# PHP ini
ADD .deploy/php/php.prod.ini /usr/local/etc/php/php.ini

# Копируем собранные расширения и их ini
COPY --from=builder /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=builder /usr/local/etc/php/conf.d/     /usr/local/etc/php/conf.d/

# Код приложения
COPY --from=builder --chown=www-data:www-data /var/www /var/www
WORKDIR /var/www

# Supervisor
COPY ./.deploy/php/supervisord.conf /etc/supervisord.conf

USER www-data
EXPOSE 9000
CMD ["supervisord", "-c", "/etc/supervisord.conf"]
