FROM php:apache

RUN a2enmod rewrite

RUN useradd -ms /bin/sh -u 1001 app
USER app

COPY . /var/www/html