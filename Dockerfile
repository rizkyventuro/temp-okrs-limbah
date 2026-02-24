FROM php:8.3-cli AS base

WORKDIR /app

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    pcntl \
    pdo_pgsql \
    pgsql \
    zip \
    opcache \
    gd \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*


FROM base AS builder

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

COPY . .
RUN composer dump-autoload --optimize --classmap-authoritative

FROM base AS production

ENV OCTANE_SERVER=frankenphp
ENV OCTANE_HOST=0.0.0.0
ENV OCTANE_PORT=8000
ENV OCTANE_WORKERS=auto
ENV OCTANE_MAX_REQUESTS=1000

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY docker/php/opcache.ini $PHP_INI_DIR/conf.d/opcache.ini
COPY docker/php/frankenphp.ini $PHP_INI_DIR/conf.d/frankenphp.ini

COPY --from=builder --chown=www-data:www-data /app /app
COPY --chown=www-data:www-data public/build /app/public/build

RUN mkdir -p storage/framework/{cache,sessions,views,testing} \
    storage/logs \
    bootstrap/cache && \
    chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN composer require laravel/octane --no-interaction --no-scripts --optimize-autoloader && \
    php artisan package:discover --ansi && \
    php artisan octane:install --server=frankenphp --no-interaction

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

USER www-data

EXPOSE 8000

HEALTHCHECK --interval=30s --timeout=10s --retries=3 --start-period=40s \
    CMD php artisan octane:status || exit 1

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

CMD ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=8000"]
