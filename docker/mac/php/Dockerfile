ARG FPM_PORT=9000
ARG PHP_VERSION=8.5

FROM php:${PHP_VERSION}-fpm

ARG DIR
ARG USER=www-data

WORKDIR ${DIR}

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN usermod -d /home/${USER} ${USER}
RUN apt-get update && apt-get install -y \
    curl \
    zip \
    unzip \
    git \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zlib1g-dev \
    libonig-dev \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg
RUN docker-php-ext-install \
    xml \
    mbstring \
    mysqli \
    pdo \
    pdo_pgsql \
    pgsql \
    zip \
    bcmath \
    gd

RUN mkdir -p /home/${USER}/.composer && chown -R ${USER}:${USER} /home/${USER}

USER ${USER}
EXPOSE ${FPM_PORT}
CMD ["php-fpm"]
