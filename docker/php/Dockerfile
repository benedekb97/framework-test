FROM php:8.1-fpm

RUN docker-php-ext-install pdo pdo_mysql

RUN apt-get update && apt-get install -y cron zlib1g-dev libpng-dev git wget nano libicu-dev libzip-dev zip unzip

RUN docker-php-ext-install gd zip

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
  && php composer-setup.php \
  && php -r "unlink('composer-setup.php');"

RUN mv composer.phar /usr/local/bin/composer
