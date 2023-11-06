FROM php:7.4
RUN apt-get update && \
    apt-get install -y libpq-dev && \
    pecl install pdo_pgsql && \
    docker-php-ext-enable pdo_pgsql
WORKDIR /app
COPY . /app

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install

CMD ["php", "-S", "0.0.0.0:8000"]