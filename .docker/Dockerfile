FROM shinsenter/php:8.4-fpm-nginx

RUN apt-get update && apt-get install -y \
    curl \
    wget \
    git \
    unzip \
    mariadb-client \
    pv \
    sudo

RUN phpaddmod hash iconv mbstring xml

# Opcache
COPY ./opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Create partage/composer dir
RUN mkdir -p /var/www/partage /var/www/.composer /var/www/.local
RUN chown -R www-data:www-data /var/www

# Workdir
WORKDIR /var/www/html
