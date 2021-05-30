FROM php:8-apache
LABEL maintainer='Edigar Herculano <edigarhdev@gmail.com>'

RUN a2enmod rewrite

# Install Composer
RUN apt-get update \
&& apt-get install -y curl git unzip libpq-dev \
&& curl -s https://getcomposer.org/installer | php \
&& mv composer.phar /usr/local/bin/composer

RUN docker-php-ext-install pdo_mysql

RUN pecl install xdebug && docker-php-ext-enable xdebug

RUN mkdir /app
COPY ./ /app/
RUN rm -r /var/www/html && ln -s /app/public /var/www/html

WORKDIR /app/

EXPOSE 80
