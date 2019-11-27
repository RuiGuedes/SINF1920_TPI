FROM php:7.3-alpine

#Timezone
ENV TZ=Europe/Lisbon
RUN apk add tzdata
RUN ls /usr/share/zoneinfo
RUN cp /usr/share/zoneinfo/Europe/Lisbon /etc/localtime

#Install dependencies
RUN apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    curl-dev \
    imagemagick-dev \
    libtool \
    libxml2-dev \
    postgresql-dev

RUN apk add --no-cache \
	g++ \
	gcc \
	curl \
	imagemagick \
	openssh-client \
	rsync \
	libtool \
	libxml2-dev \
	postgresql-dev \
	postgresql-libs \
	bash \
	git \
	libc-dev \
	libpng-dev \
	make \
	nodejs \
	nodejs-npm \
	yarn \
	rsync \
	zlib-dev \
	libzip-dev

RUN pecl install \
	imagick \
	xdebug

RUN docker-php-ext-enable \
	imagick \
	xdebug

RUN docker-php-ext-configure zip --with-libzip
RUN docker-php-ext-install \
	calendar \
	curl \
	exif \
	iconv \
	mbstring \
	pdo \
	pdo_pgsql \
	pcntl \
	tokenizer \
	xml \
	gd \
	zip \
	bcmath

# Install composer
ENV COMPOSER_HOME /composer
ENV PATH ./vendor/bin:/composer/vendor/bin:$PATH
ENV COMPOSER_ALLOW_SUPERUSER 1
RUN curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer

# Cleanup dev dependencies
RUN apk del -f .build-deps

# Setup working directory
WORKDIR /var/www

COPY . ./

# Ensure that there is a .env file based on the example configs (that can be overriden using environment variables)
# (Copying without overwriting: https://superuser.com/questions/1355711/busybox-recursive-non-overwriting-file-copy)
RUN false | cp -i .env.dev .env
RUN composer install

RUN php artisan package:discover --ansi
RUN php artisan key:generate --ansi

RUN php artisan cache:clear
RUN php artisan config:clear
RUN php artisan route:clear

# Necessary so that the environment variables are applied
# If config:cache was run, Laravel would not look at the environment variables any more
# As such, so that not only the .env file is sourced, this command is run
RUN php artisan config:clear

CMD ["php", "artisan", "serve", "--host=0.0.0.0"]

