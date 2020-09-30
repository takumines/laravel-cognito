FROM php:7.3-fpm

RUN apt-get update \
  && apt-get install -y zlib1g-dev zip curl wget

# MySQL
RUN apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_mysql

# GD
RUN apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install -j$(nproc) gd

# Exif
RUN docker-php-ext-install -j$(nproc) exif

# ImageMagick
RUN apt-get install -y imagemagick && apt-get install -y --no-install-recommends libmagickwand-dev
RUN pecl install imagick && docker-php-ext-enable imagick

# Human Language and Character Encoding Support
RUN apt-get install -y zlib1g-dev libicu-dev g++
RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl

# nodejs install
RUN curl -sL https://deb.nodesource.com/setup_12.x | bash - && \
  apt-get install -y nodejs

# Composer install
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer config -g process-timeout 3600
RUN composer config -g repos.packagist composer https://packagist.jp
RUN composer global require hirak/prestissimo

COPY php.ini /usr/local/etc/php/php.ini

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /composer
ENV PATH $PATH:/composer/vendor/bin

WORKDIR /var/www