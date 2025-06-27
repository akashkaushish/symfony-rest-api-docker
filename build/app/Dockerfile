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
    && pecl install apcu xdebug \
    && docker-php-ext-enable apcu xdebug \
    && pecl clear-cache \
    && apk del .build-dependencies

# Copy Symfony app code
COPY project ${APPDIR}

# Optional: validate nginx config
RUN nginx -t

EXPOSE 8080
CMD ["sh", "-c", "crond && php-fpm -D && nginx -g 'daemon off;'"]

# Production stage
FROM base AS prod

ENV APP_ENV=prod

RUN apk add --no-cache nginx php81-pecl-apcu \
    && apk add --no-cache --update --virtual .build-dependencies $PHPIZE_DEPS \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && pecl clear-cache \
    && apk del .build-dependencies

# Build production application
FROM prod AS builder

COPY project ${APPDIR}

ARG DCA_BACKEND_PRIVATE_KEY
COPY --from=composer/composer:lts /usr/bin/composer /usr/bin/composer

# Add SSH key for private repo access
RUN mkdir -p ~/.ssh && ssh-keyscan -t rsa gitlab.publicplan.cloud >> ~/.ssh/known_hosts \
    && eval $(ssh-agent -s) \
    && echo -n ${DCA_BACKEND_PRIVATE_KEY} | base64 -di | ssh-add - \
    && cd ${APPDIR} \
    && composer install --no-dev --no-scripts

# Warmup cache
RUN rm -rf ${APPDIR}/var && mkdir ${APPDIR}/var \
    && cd ${APPDIR} && bin/console cache:warmup \
    && chown www-data -R ${APPDIR}/var

# Final runtime container
FROM prod AS runner

ARG CI_COMMIT_SHORT_SHA
ENV CI_COMMIT_SHORT_SHA=$CI_COMMIT_SHORT_SHA

RUN deluser --remove-home www-data \
    && addgroup -g 10001 -S symfony \
    && adduser -u 10001 -S symfony -G symfony \
    && sed -i '/user nginx;/d' /etc/nginx/nginx.conf \
    && nginx -t

COPY --from=builder ${APPDIR} ${APPDIR}

RUN chown -R symfony:symfony /var/lib/nginx/logs/* /run/nginx/* /var/lib/nginx

USER symfony
RUN touch /run/nginx/nginx.pid

EXPOSE 8080
CMD ["sh", "-c", "crond && php-fpm -D && nginx -g 'daemon off;'"]
