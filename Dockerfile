

FROM richarvey/nginx-php-fpm:latest

WORKDIR /var/www/html

# Copy application files
COPY . .

RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - 
&& apt-get install -y nodejs 
&& npm install -g pnpm 
&& pnpm install

RUN pnpm run build

# Image config/Environment setup
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel config
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

EXPOSE 80

CMD ["/start.sh"]
