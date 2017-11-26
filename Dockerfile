FROM php:apache

COPY composer.* /var/www/

RUN curl

RUN set -ex; \
    cd /var/www/; \
    wget https://getcomposer.org/download/1.5.2/composer.phar; \
    php composer.phar install; \
    php composer.phar clear-cache; \
    rm composer.phar

COPY ./ /var/www/

RUN ls -al ..
