FROM wordpress:php8.4-apache

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html/wp-content
