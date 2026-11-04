FROM wordpress:php8.4-apache

COPY . /var/www/html/

RUN rm -f /var/www/html/wp-config.php \
    && chown -R www-data:www-data /var/www/html/wp-content