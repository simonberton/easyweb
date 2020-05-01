FROM ubuntu:18.04

ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=UTC

RUN apt-get clean -y && apt-get update && apt-get upgrade -y
RUN apt-get install -y tzdata apt-utils vim curl git unzip

RUN apt-get update && apt-get install -y php php-fpm php-mysql php-xml php-curl php-zip php-mbstring php-xdebug php-imagick php-gd php-gmp

RUN apt-get update &&\
    apt-get install --no-install-recommends --assume-yes --quiet ca-certificates curl git &&\
    rm -rf /var/lib/apt/lists/*

RUN apt-get install php-xdebug
#RUN echo 'zend_extension="/usr/local/lib/php/extensions/no-debug-non-zts-20151012/xdebug.so"' >> /usr/local/etc/php/php.ini
#RUN echo 'xdebug.remote_port=9000' >> /usr/local/etc/php/php.ini
#RUN echo 'xdebug.remote_enable=1' >> /usr/local/etc/php/php.ini
#RUN echo 'xdebug.remote_connect_back=1' >> /usr/local/etc/php/php.ini

RUN curl -sL https://deb.nodesource.com/setup_12.x | bash -
RUN apt-get install -y nodejs
RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add -
RUN echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list
RUN apt-get update && apt-get install -y yarn
RUN export PATH="$PATH:`yarn global bin`"
RUN composer require encore
RUN yarn install


ADD etc/php/7.2/fpm/conf.d/ /etc/php/7.2/fpm/conf.d/
ADD etc/php/7.2/cli/conf.d/ /etc/php/7.2/cli/conf.d/
ADD etc/php/7.2/fpm/pool.d/www.conf /etc/php/7.2/fpm/pool.d/

RUN mkdir /run/php

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

WORKDIR /home/app

CMD ["php-fpm7.2", "-F"]
