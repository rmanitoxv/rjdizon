FROM php:latest

WORKDIR /app
COPY . /app

RUN apt-get update && apt-get upgrade -y
RUN apt-get install -y libpq-dev
RUN docker-php-ext-install pgsql
RUN docker-php-ext-install pdo_pgsql

RUN apt-get install -y libpng-dev zlib1g-dev && \
    docker-php-ext-install gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install

CMD ["php", "-S", "0.0.0.0:8000"]