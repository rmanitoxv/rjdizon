FROM php:latest
WORKDIR /app
COPY . /app

RUN docker-php-ext-install pgsql pdo_pgsql

CMD ["php", "-S", "0.0.0.0:8000"]