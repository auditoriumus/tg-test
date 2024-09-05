FROM php:8.2-fpm-alpine

RUN docker-php-ext-install -j$(nproc) pdo_mysql && \
    php -m

RUN adduser -D new_user
USER new_user
