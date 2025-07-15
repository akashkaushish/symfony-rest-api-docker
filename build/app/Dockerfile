FROM php:8.3.4-fpm-alpine3.18 AS base

ARG APPDIR=/var/www/project
WORKDIR ${APPDIR}

# Copy default Nginx config
COPY build/app/default.conf /etc/nginx/http.d/default.conf

# Install PHP extensions required for MariaDB (pdo_mysql)
RUN apk add --no-cache unzip openssh git \
        openssl-dev curl-dev mariadb-dev \
    && docker-php-ext-install pdo pdo_mysql

# Install mariadb-client
RUN apk update && apk add mariadb-client

# Setup crontab
COPY build/app/cron /usr/local/etc/crons/cron
RUN cat /usr/local/etc/crons/cron >> /var/spool/cron/crontabs/root

# Build stage for development environment
FROM base AS dev

COPY --from=composer/composer:lts /usr/bin/composer /usr/bin/composer

# Install development tools and PHP extensions
RUN apk add --no-cache nginx php81-pecl-apcu \
    && apk add --update --no-cache --virtual .build-dependencies $PHPIZE_DEPS linux-headers \
    && pecl install redis apcu xdebug \
    && docker-php-ext-enable redis apcu xdebug \
    && pecl clear-cache \
    && apk del .build-dependencies \
    && rm -rf /tmp/*

RUN echo "extension=redis.so" > /usr/local/etc/php/conf.d/docker-php-ext-redis.ini

# Copy Symfony app code
COPY project ${APPDIR}

# Optional: validate nginx config
RUN nginx -t

EXPOSE 8080
CMD ["sh", "-c", "crond && php-fpm -D && nginx -g 'daemon off;'"]
