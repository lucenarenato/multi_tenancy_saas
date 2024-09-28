# Use a imagem base do PHP 8.2 FPM Alpine
FROM php:8.2-fpm-alpine

# Instalar dependências necessárias e ferramentas
RUN apk --update add --no-cache \
    wget \
    curl \
    git \
    grep \
    build-base \
    libmemcached-dev \
    libmcrypt-dev \
    libxml2-dev \
    imagemagick-dev \
    pcre-dev \
    libtool \
    make \
    autoconf \
    g++ \
    cyrus-sasl-dev \
    libgsasl-dev \
    supervisor \
    libpq-dev \
    libpng-dev \
    # Instalar ferramentas necessárias para o PECL
    $PHPIZE_DEPS

# Instalar o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# Instalar extensões PHP comuns
RUN docker-php-ext-install mysqli pdo pdo_mysql xml

RUN docker-php-ext-configure pcntl --enable-pcntl \
  && docker-php-ext-install pcntl;

RUN apk add --no-cache linux-headers
RUN docker-php-ext-install sockets

RUN apk add --no-cache imagemagick imagemagick-dev

# Instalar extensões PECL
RUN pecl channel-update pecl.php.net \
    && pecl install memcached \
    && pecl install imagick-3.6.0 \
    && docker-php-ext-enable imagick \
    && docker-php-ext-enable memcached

# Configurar e instalar a extensão GD
RUN apk update && \
    apk add freetype-dev \
            php82-gd \
            libmcrypt-dev \
            libpng-dev \
            libjpeg \
            libpng-dev && \
    docker-php-ext-configure gd --enable-gd --with-freetype && \
    docker-php-ext-install gd
# RUN docker-php-ext-configure gd --with-freetype
RUN docker-php-ext-install -j "$(nproc)" gd

# Configurar e instalar as extensões PostgreSQL
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pgsql pdo_pgsql

# Instalar a extensão MongoDB usando PECL
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

RUN docker-php-ext-configure zip --with-libzip
RUN apk add --no-cache libzip-dev && docker-php-ext-install zip

# Limpar cache do APK e criar diretório de trabalho
RUN rm -rf /var/cache/apk/* \
    && mkdir -p /var/www
