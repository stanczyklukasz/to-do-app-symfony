FROM php:8.0.3-apache-buster

COPY --from=composer:2.0.9 /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get -y install unzip

COPY ./vhost.conf /etc/apache2/sites-available/projectsite.conf
RUN a2ensite projectsite

COPY php.ini "$PHP_INI_DIR/php.ini"

RUN apt-get -y install libicu-dev
RUN docker-php-ext-install intl opcache

RUN docker-php-ext-install pdo_mysql