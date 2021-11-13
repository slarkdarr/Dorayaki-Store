FROM php:8.0.0rc1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y git

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql

# install sqlite extension
RUN apt-get install php-sqlite3

# Set working directory
WORKDIR /var/www