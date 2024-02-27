FROM php:7.4-fpm

COPY ./docker/php/php.ini /usr/local/etc/php/

RUN apt-get update && apt-get install -y \
        apt-utils \
        gnupg \
        default-mysql-client \
        cmake \
        wget \
    && echo "deb http://packages.dotdeb.org jessie all" >> /etc/apt/sources.list \
    && echo "deb-src http://packages.dotdeb.org jessie all" >> /etc/apt/sources.list \
    && curl -sS --insecure https://www.dotdeb.org/dotdeb.gpg | apt-key add - \
    && apt-get update && apt-get install -y \
        zlib1g-dev \
        libzip-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd exif \
    && docker-php-ext-install zip pdo_mysql \
    && pecl install xdebug-2.9.0 \
    && docker-php-ext-enable xdebug \
    && curl -sL https://deb.nodesource.com/setup_12.x | bash - \
    && apt-get install -y nodejs \
        libxrender1 \
        libfontconfig1 \
        libxext6 \
        fonts-ipafont \
        wkhtmltopdf

#composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /composer
ENV PATH $PATH:/composer/vendor/bin

COPY ./php /var/www/app
COPY ./docker/php/startup.sh /usr/local/bin/startup.sh

WORKDIR /var/www/app
RUN chown -R www-data:www-data . \
    && chmod 777 /usr/local/bin/startup.sh \
    && chmod -R 775 /var/www/app

CMD ["/usr/local/bin/startup.sh","php-fpm","-F"]