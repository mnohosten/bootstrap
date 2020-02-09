FROM krizius/php:7.4-apache-buster-zts

RUN apt update && \
    apt install -y git pkg-config

RUN apt-get -y --no-install-recommends install cmake make git gcc g++ libbz2-dev libstxxl-dev libstxxl1v5 libxml2-dev \
    libzip-dev libboost-all-dev lua5.2 liblua5.2-dev libtbb-dev -o APT::Install-Suggests=0 -o APT::Install-Recommends=0
RUN apt-get install -y libzip-dev unzip
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install mysqli
RUN pecl install -o -f parallel && docker-php-ext-enable parallel
RUN docker-php-ext-install zip
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install opcache
RUN apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libmcrypt-dev libxml2-dev
RUN docker-php-ext-install gd
RUN a2enmod rewrite
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer
RUN composer global require hirak/prestissimo
