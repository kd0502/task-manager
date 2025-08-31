FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev libonig-dev default-mysql-client netcat-openbsd \
    && docker-php-ext-install intl pdo pdo_mysql opcache zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh \
    && sed -i 's/\r$//' /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

WORKDIR /var/www/task-manager
