# ---- Stage 1: PHP dependencies (composer, no dev) ----
FROM php:7.4.33-cli AS vendor
# Bullseye is EOL: repos moved to archive.debian.org
RUN sed -i -e 's|deb.debian.org/debian-security|archive.debian.org/debian-security|g' \
           -e 's|deb.debian.org/debian|archive.debian.org/debian|g' \
           -e '/bullseye-updates/d' /etc/apt/sources.list \
    && apt-get -o Acquire::Check-Valid-Until=false update \
    && apt-get install -y --no-install-recommends git unzip \
    && rm -rf /var/lib/apt/lists/*
# Laravel 5.8's PackageManifest cannot parse Composer 2's installed.json: use Composer 1
COPY --from=composer:1.10 /usr/bin/composer /usr/bin/composer
WORKDIR /app
COPY composer.json composer.lock ./
# The autoloader is generated afterwards, once the sources (classmap) are present
RUN composer install --no-dev --no-scripts --no-autoloader --no-interaction --prefer-dist \
        --ignore-platform-reqs
COPY app/ app/
COPY bootstrap/ bootstrap/
COPY config/ config/
COPY database/ database/
COPY routes/ routes/
RUN composer dump-autoload --no-dev --optimize --no-scripts --no-interaction

# ---- Stage 2: runtime (Apache + PHP 7.4 + MariaDB) ----
FROM php:7.4.33-apache

RUN sed -i -e 's|deb.debian.org/debian-security|archive.debian.org/debian-security|g' \
           -e 's|deb.debian.org/debian|archive.debian.org/debian|g' \
           -e '/bullseye-updates/d' /etc/apt/sources.list \
    && apt-get -o Acquire::Check-Valid-Until=false update && apt-get install -y --no-install-recommends \
        libjpeg-dev libpng-dev libfreetype6-dev libxml2-dev libonig-dev libzip-dev \
        mariadb-server supervisor \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql gd mbstring bcmath zip exif

RUN a2enmod rewrite

# Apache on 8080 (single exposed port)
RUN sed -i -e 's/Listen 80$/Listen 8080/' -e 's/Listen 443$/Listen 8443/' /etc/apache2/ports.conf

COPY docker/my.cnf /etc/mysql/mariadb.conf.d/99-app.cnf
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/zz-app.ini
COPY docker/apache-vhost.conf /etc/apache2/sites-available/000-default.conf

# Application: public_html/ must sit next to vendor/ (see public_html/index.php)
WORKDIR /var/www/app
COPY artisan server.php composer.json ./
COPY app/ app/
COPY bootstrap/ bootstrap/
COPY config/ config/
COPY database/ database/
COPY resources/ resources/
COPY routes/ routes/
COPY storage/ storage/
COPY public_html/ public_html/
COPY --from=vendor /app/vendor/ vendor/

# public_html/storage ships as a placeholder dir; replace it with the storage:link symlink.
# Package discovery needs an app key but no database.
RUN rm -rf public_html/storage \
    && ln -s /var/www/app/storage/app/public public_html/storage \
    && mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs \
    && APP_KEY=base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA= php artisan package:discover \
    && chown -R www-data:www-data storage bootstrap/cache

# Strip CR in case the checkout used CRLF (Windows)
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh /etc/supervisord.conf \
    && chmod +x /usr/local/bin/entrypoint.sh

# Demo-only defaults; override at runtime (docker run -e ...)
ENV APP_ENV=production \
    APP_DEBUG=false \
    APP_URL=http://localhost:8080 \
    DB_DATABASE=instructores \
    DB_USERNAME=instructores \
    DB_PASSWORD=instructores-demo \
    MAIL_DRIVER=log \
    FILESYSTEM_DRIVER=public

# MariaDB datadir (also holds the generated APP_KEY and the first-run marker)
VOLUME /var/lib/mysql

EXPOSE 8080
ENTRYPOINT ["entrypoint.sh"]
CMD ["supervisord", "-n", "-c", "/etc/supervisord.conf"]
