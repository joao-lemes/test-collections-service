FROM php:8.4-fpm-alpine

RUN apk add --no-cache mysql-client msmtp perl wget procps shadow libzip libpng libjpeg-turbo libwebp freetype icu

RUN apk add --no-cache --virtual build-dependencies \
    icu-dev icu-libs zlib-dev g++ make automake autoconf libzip-dev \
    libpng-dev libwebp-dev libjpeg-turbo-dev freetype-dev && \
    docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg --with-webp && \
    docker-php-ext-install gd mysqli pdo_mysql opcache && \
    apk del build-dependencies && \
    rm -rf /usr/src/php*
    
RUN apk add --no-cache autoconf gcc g++ make && \
    pecl install pcov && \
    docker-php-ext-enable pcov && \
    echo "pcov.enabled=1" > /usr/local/etc/php/conf.d/pcov.ini && \
    apk del autoconf gcc g++ make

RUN wget https://getcomposer.org/composer-stable.phar -O /usr/local/bin/composer && chmod +x /usr/local/bin/composer

RUN apk add nano vim

ENV TZ=America/Sao_Paulo
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

CMD ["php-fpm"]
