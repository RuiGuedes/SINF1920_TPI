FROM ubuntu:18.04

# Timezone
ENV TZ=Europe/Lisbon
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Install dependencies
RUN apt-get update

RUN apt-get install -y --no-install-recommends \
    git \
    composer \
    libpq-dev \
    vim \
    nginx \
    php7.2 \
    php7.2-fpm \
    php7.2-mbstring \
    php7.2-xml \
    php7.2-pgsql \
    php7.2-bcmath \
    php7.2-soap \
    php7.2-curl \
    php7.2-zip \
    php7.2-xdebug \
    postgresql-client \
    curl 

# Copy project code and install project dependencies
COPY . /var/www/
RUN chown -R www-data:www-data /var/www/

# Copy project configurations
COPY ./etc/php/php.ini /usr/local/etc/php/conf.d/php.ini
COPY ./etc/nginx/default.conf /etc/nginx/sites-enabled/default
COPY .env.dev /var/www/.env
COPY docker_run.sh /docker_run.sh
RUN mkdir /var/run/php

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www

RUN rm -rf public/storage
RUN composer install

RUN php artisan cache:clear
RUN php artisan config:clear
RUN php artisan route:clear

RUN php artisan config:cache
RUN php artisan config:clear
RUN php artisan storage:link

# Start command
CMD sh /docker_run.sh