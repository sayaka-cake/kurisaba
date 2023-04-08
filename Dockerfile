FROM php:5.6-apache

# install packages
RUN apt-get update && \
    docker-php-ext-install \
    mysqli \
    pdo \
    pdo_mysql

RUN apt-get install -y git

# copy PHP project repository content from Github to /var/www/html
RUN git clone https://github.com/sayaka-cake/kurisaba /var/www/html

# set up dwoo templates folder
RUN mkdir -p /var/www/html/tmp
RUN chown www-data:www-data /var/www/html/tmp
RUN chmod 777 /var/www/html/tmp

RUN a2enmod rewrite
EXPOSE 80

CMD ["apache2-foreground"]