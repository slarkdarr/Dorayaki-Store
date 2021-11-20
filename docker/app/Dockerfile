FROM php:8.0.0rc1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y libxml2-dev

RUN apt-get clean -y

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql

# install sqlite extension
RUN apt install sqlite3 -y

# Install soap client
RUN docker-php-ext-install soap && docker-php-ext-enable soap

# Set working directory
WORKDIR /var/www