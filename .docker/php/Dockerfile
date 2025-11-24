FROM php:7.2-apache

# Install PDO extension and any other extensions you need
RUN docker-php-ext-install pdo pdo_mysql

# Install Git, zip, and unzip
RUN apt-get update && \
    apt-get install -y git zip unzip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory in the container
WORKDIR /var/www/html

# Copy the current directory contents into the container
COPY . .

# Install project dependencies
# RUN composer install

# Expose port 80 to the outside world
EXPOSE 80

# Configure Apache Document Root
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Use the custom document root, this assumes your `index.php` is located in the `public` directory
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Enable mod_rewrite for URL rewriting
RUN a2enmod rewrite

# RUN  a2enmod rewrite

# RUN apt-get update     \
#     && apt-get -y install libzip-dev     \
#     && apt-get install -y libpng-dev     \
#     && apt-get install -y libc-client-dev     \
#     && apt-get install -y libicu-dev

# RUN  docker-php-ext-install zip
# RUN  docker-php-ext-enable zip
# RUN  docker-php-ext-install gd
# RUN  docker-php-ext-enable gd
# RUN  docker-php-ext-install intl
# RUN  docker-php-ext-enable intl
# RUN  docker-php-ext-install pdo pdo_mysql
# RUN  docker-php-ext-enable pdo pdo_mysql
# RUN  apt-get install autoconf

# RUN  curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# RUN  composer self-update --1

# ENV APACHE_DOCUMENT_ROOT=/var/www/public

# RUN  sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
# RUN  sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# WORKDIR /var/www

# RUN  useradd -u 1000 eurobydleni
# RUN  adduser eurobydleni www-data
# RUN  chown -R eurobydleni:www-data /var/www

# RUN  cd /usr/local/etc/php/conf.d/ \
#     &&   echo 'memory_limit = -1' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini

# #End with running the original command
# exec /usr/sbin/apache2ctl -D FOREGROUND