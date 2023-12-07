# Use an official PHP 7.3 image with PHP-FPM
FROM php:7.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libonig-dev \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libxml2-dev

# Install required PHP extensions
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install gd mbstring pdo pdo_mysql zip xml

# Install Composer globally
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN php -r "unlink('composer-setup.php');"

# Set the working directory
WORKDIR /app

# Copy your Laravel application code into the container
COPY ./project /app

# Expose port 8000 for PHP-FPM
EXPOSE 8000
