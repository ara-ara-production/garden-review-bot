# установка ноды
#FROM node:22-alpine AS node

FROM php:8.4-fpm-alpine
ARG uid=1000
ARG user=ifan

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

RUN apk add --no-cache curl-dev \
    && docker-php-ext-install curl


#COPY --from=node /usr/lib /usr/lib
#COPY --from=node /usr/local/lib /usr/local/lib
#COPY --from=node /usr/local/include /usr/local/include
#COPY --from=node /usr/local/bin /usr/local/bin

# Установка и настройка расширений для php
RUN docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype
RUN docker-php-ext-install -j$(nproc) gd pdo pgsql pdo_pgsql

RUN apk add --no-cache $PHPIZE_DEPS linux-headers \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del $PHPIZE_DEPS linux-headers

RUN echo "allow_url_fopen=On" > /usr/local/etc/php/conf.d/allow_url_fopen.ini


RUN wget https://raw.githubusercontent.com/composer/getcomposer.org/76a7060ccb93902cd7576b67264ad91c8a2700e2/web/installer -O - -q | php -- --quiet \
  && mv composer.phar /usr/local/bin/composer

RUN apk add --no-cache $PHPIZE_DEPS autoconf gcc g++ make \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del $PHPIZE_DEPS autoconf gcc g++ make


RUN apk add --update-cache git curl zip unzip \
  && rm -rf /var/cache/apk/*

RUN adduser -u $uid -h /home/$user -D $user $user
RUN mkdir -p /home/$user/.composer && chown -R $user:$user /home/$user

