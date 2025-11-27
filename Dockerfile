# Dockerfile
FROM php:8.2-fpm-alpine
RUN apk add --no-cache nginx \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql
# ... (instalasi ekstensi lain)
COPY default.conf /etc/nginx/conf.d/default.conf
WORKDIR /app
COPY . /app
RUN composer install --no-dev
# ... (permission dan CMD)
CMD php-fpm -D && nginx -g "daemon off;"