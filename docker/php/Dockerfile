# Use the latest supported PHP version
FROM php:8.5-apache

# Install system packages required by Composer
RUN apt-get update \
    && apt-get install -y unzip git libzip-dev \
    && docker-php-ext-install mysqli zip \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install the mysqli extension required for MySQL connections
RUN docker-php-ext-install mysqli

# Enable Apache mod_rewrite for pretty URLs and routing
RUN a2enmod rewrite