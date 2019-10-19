FROM php:7.3-fpm

RUN apt update \
    && apt install -y \
        nginx \
        libgmp-dev \
        libmagickwand-dev \
        ghostscript \
        zlib1g-dev \
        libpng-dev \
        libjpeg62-turbo-dev \
        jpegoptim \
        optipng \
        pngquant \
        gifsicle \
        gnupg git-core \
        --no-install-recommends \
    && yes | pecl install imagick-3.4.3 && docker-php-ext-configure gd --with-jpeg-dir=/usr/lib/x86_64-linux-gnu/ && docker-php-ext-enable imagick \
    && docker-php-ext-install bcmath gmp pdo pdo_mysql pcntl gd exif \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Adding our services to the S6 expected location
COPY resources/docker/services.d /etc/services.d

# Adding the S6 overlay
ADD https://github.com/just-containers/s6-overlay/releases/download/v1.22.1.0/s6-overlay-amd64.tar.gz /tmp/
RUN tar xzf /tmp/s6-overlay-amd64.tar.gz -C /

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /tmp
ENV COMPOSER_VERSION 1.8.6

# Install Composer.
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('sha384', 'composer-setup.php') === 'a5c698ffe4b8e849a443b120cd5ba38043260d5c4023dbf93e1558871f1f07f58274fc6f4c93bcfd858c6bd0775cd8d1') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/bin/composer

# Copy the application files to run correct location
COPY --chown=www-data:www-data . /var/www/html

WORKDIR /var/www/html

# Enable the site for Nginx
ADD resources/docker/default.conf /etc/nginx/sites-enabled/default

EXPOSE 80 443

ENTRYPOINT ["/init"]
