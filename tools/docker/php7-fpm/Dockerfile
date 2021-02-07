FROM php:7.4-fpm
RUN apt-get update
RUN apt-get install -y zlib1g-dev libpq-dev git libicu-dev libxml2-dev libzip-dev zip unzip \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    && docker-php-ext-install zip xml
RUN apt-get install -y \
        libonig-dev \
    && docker-php-ext-install iconv mbstring
RUN apt-get update -y && apt-get install -y sendmail libpng-dev
RUN apt-get update && apt-get install -y --no-install-recommends \
        imagemagick
RUN apt-get update && apt-get install -y \
libfreetype6-dev libjpeg62-turbo-dev \
libgd-dev
RUN docker-php-ext-configure gd \
--with-freetype=/usr/include/ \
--with-jpeg=/usr/include/
RUN docker-php-ext-install gd
RUN docker-php-ext-install exif
RUN docker-php-ext-configure exif \
            --enable-exif

RUN curl -sL https://deb.nodesource.com/setup_12.x | bash - \
    && apt-get install -y nodejs
RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
    && echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list \
    && apt-get update && apt-get install -y yarn
RUN apt-get update && apt-get install -y \
    && pecl install redis \
    && docker-php-ext-enable redis
RUN curl --insecure https://getcomposer.org/composer.phar -o /usr/bin/composer && chmod +x /usr/bin/composer
WORKDIR /var/www/application/
