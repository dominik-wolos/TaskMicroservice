ARG PHP_VERSION=8.3.3
ARG NODE_VERSION=20.11.1
ARG NGINX_VERSION=1.21
ARG ALPINE_VERSION=3.19
ARG NODE_ALPINE_VERSION=3.19
ARG COMPOSER_VERSION=2.4
ARG PHP_EXTENSION_INSTALLER_VERSION=latest

FROM composer:${COMPOSER_VERSION} AS composer

FROM mlocati/php-extension-installer:${PHP_EXTENSION_INSTALLER_VERSION} AS php_extension_installer

FROM php:${PHP_VERSION}-fpm-alpine${ALPINE_VERSION} AS base
ARG HYFINDR_PACKAGIST_SECRET
ENV HYFINDR_PACKAGIST_SECRET=${HYFINDR_PACKAGIST_SECRET}

# persistent / runtime deps
RUN apk add --no-cache \
        acl \
        file \
        gettext \
        unzip \
    ;

COPY --from=php_extension_installer /usr/bin/install-php-extensions /usr/local/bin/

# default PHP image extensions
# ctype curl date dom fileinfo filter ftp hash iconv json libxml mbstring mysqlnd openssl pcre PDO pdo_sqlite Phar
# posix readline Reflection session SimpleXML sodium SPL sqlite3 standard tokenizer xml xmlreader xmlwriter zlib
RUN install-php-extensions apcu exif gd intl pdo_mysql opcache zip sockets

COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY docker/php/prod/php.ini        $PHP_INI_DIR/php.ini
COPY docker/php/prod/opcache.ini    $PHP_INI_DIR/conf.d/opcache.ini

# copy file required by opcache preloading
COPY config/preload.php /srv/app/config/preload.php

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_MEMORY_LIMIT=-1
RUN set -eux; \
    composer clear-cache
ENV PATH="${PATH}:/root/.composer/vendor/bin"

WORKDIR /srv/app

# build for production
ENV APP_ENV=prod

# prevent the reinstallation of vendors at every changes in the source code
COPY composer.* ./
RUN set -eux; \
    composer install --prefer-dist --no-autoloader --no-interaction --no-scripts --no-progress --no-dev; \
    composer clear-cache

# copy only specifically what we need
COPY .env .env.prod ./
COPY assets assets/
COPY bin bin/
COPY config config/
COPY public public/
COPY src src/
COPY templates templates/
COPY translations translations/

RUN set -eux; \
    mkdir -p var/cache var/log; \
    composer dump-autoload --classmap-authoritative; \
    APP_SECRET='' composer run-script post-install-cmd; \
    chmod +x bin/console; sync

VOLUME /srv/app/var

VOLUME /srv/app/public/media

COPY docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]

FROM node:${NODE_VERSION}-alpine${NODE_ALPINE_VERSION} AS app_node

WORKDIR /srv/app

RUN set -eux; \
	apk add --no-cache --virtual .build-deps \
        acl \
		g++ \
		gcc \
		make \
	;

COPY --from=base /srv/app/vendor/symfony/ux-turbo/assets  /srv/app/vendor/symfony/ux-turbo/assets

# prevent the reinstallation of vendors at every changes in the source code
COPY package.json yarn.* ./
RUN set -eux; \
    yarn install; \
    yarn cache clean

COPY --from=base /srv/app/assets ./assets


COPY webpack.config.js ./
RUN yarn encore prod

COPY docker/node/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]
CMD ["yarn", "prod"]

FROM base AS app_php_prod

COPY --from=app_node /srv/app/public/build public/build

FROM nginx:${NGINX_VERSION}-alpine AS app_nginx

COPY docker/nginx/conf.d/default.conf /etc/nginx/conf.d/

WORKDIR /srv/app

COPY --from=base        /srv/app/public public/
COPY --from=app_node /srv/app/public public/

FROM app_php_prod AS app_php_dev

COPY docker/php/dev/php.ini        $PHP_INI_DIR/php.ini
COPY docker/php/dev/opcache.ini    $PHP_INI_DIR/conf.d/opcache.ini

WORKDIR /srv/app

ENV APP_ENV=dev

COPY .env.test ./

RUN set -eux; \
    composer install --prefer-dist --no-autoloader --no-interaction --no-scripts --no-progress; \
    composer clear-cache

FROM app_php_prod AS app_cron

RUN set -eux; \
	apk add --no-cache --virtual .build-deps \
		apk-cron \
        acl \
	;

COPY docker/cron/crontab /etc/crontabs/root
COPY docker/cron/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]
CMD ["crond", "-f"]

FROM app_php_prod AS app_migrations_prod

RUN apk add --no-cache wget acl
COPY docker/migrations/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint
COPY --from=base /srv/app/vendor/symfony/ux-turbo/assets  /srv/app/vendor/symfony/ux-turbo/assets

ENTRYPOINT ["docker-entrypoint"]

FROM app_php_dev AS app_migrations_dev

RUN apk add --no-cache wget acl
COPY docker/migrations/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint
COPY --from=base /srv/app/vendor/symfony/ux-turbo/assets  /srv/app/vendor/symfony/ux-turbo/assets

RUN composer dump-autoload --classmap-authoritative

ENTRYPOINT ["docker-entrypoint"]
