# Use official PHP image.
FROM php:8.0-cli

# Install system dependencies and PHP extensions
RUN apt-get update && \
    apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev zlib1g-dev git unzip && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /app

# Copy composer.json and install dependencies
COPY composer.json /app/
RUN composer install

# Copy all files to the container
COPY . /app/
