FROM php:alpine

RUN apk update \
 && apk upgrade \
 && apk add --no-cache \
        sqlite-libs \
        sqlite-dev \
        sqlite \
        freetype \
        libpng \
        libjpeg-turbo \
        freetype-dev \
        libpng-dev \
        libjpeg-turbo-dev

RUN docker-php-ext-configure gd \
        --with-freetype-dir=/usr/include/ \
        --with-png-dir=/usr/include/ \
        --with-jpeg-dir=/usr/include/ \
 && docker-php-ext-install -j$(getconf _NPROCESSORS_ONLN) pdo pdo_sqlite gd exif

#RUN usermod -u 1000 www-data
#RUN adduser -D -u 1000 inikulin