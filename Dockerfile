FROM php:7.1-alpine
MAINTAINER Sam Stenvall <sam.stenvall@digia.com>

# Install phpize deps
RUN apk add --no-cache --virtual .phpize-deps $PHPIZE_DEPS

# Install the vips extension
RUN apk add --no-cache --virtual .vips-deps vips-dev \
    && pecl install vips \
    && docker-php-ext-enable vips \
    && apk add --no-cache --virtual .vips-runtime-deps vips \
    && apk del .vips-deps

# Install the exif extension
RUN docker-php-ext-install exif

# Uninstall phpize dependencies
RUN apk del .phpize-deps

WORKDIR /app
