FROM php:alpine

RUN apk add --update sqlite-libs sqlite-dev sqlite
RUN docker-php-ext-install pdo pdo_sqlite