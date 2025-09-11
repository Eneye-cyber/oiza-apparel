FROM php:8.3-fpm

# Install system dependencies + PHP extensions
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    git \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        intl \
        zip \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Allow Composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER=1

# Set working directory
WORKDIR /var/www/html

# Copy application code
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Laravel config
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr
ENV DB_CONNECTION=pgsql

# Fix Laravel permissions (for storage & cache dirs)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Run setup that doesn’t depend on DB
# RUN php artisan storage:link && \
#     php artisan config:clear && \
#     php artisan config:cache && \
#     php artisan route:cache && \
#     php artisan icons:cache && \
#     php artisan filament:cache-components && \
#     php artisan view:cache && \
#     php artisan event:cache

# Copy configs
COPY ./conf/nginx/nginx-site.conf /etc/nginx/conf.d/default.conf
COPY ./conf/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose port 80
EXPOSE 80

# Copy entrypoint script (to run migrations/seeds at runtime)
COPY ./docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Start supervisor (runs Nginx + PHP-FPM together) via entrypoint
ENTRYPOINT ["/entrypoint.sh"]
