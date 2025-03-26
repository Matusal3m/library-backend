FROM php:apache

RUN a2enmod rewrite

ARG HOST_UID=1001
ARG HOST_GID=1001

RUN usermod -u $HOST_UID www-data && \
    groupmod -g $HOST_GID www-data

COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html