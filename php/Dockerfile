FROM php:7.4-fpm

ARG USER_ID
ARG GROUP_ID

RUN if [ ${USER_ID:-0} -ne 0 ] && [ ${GROUP_ID:-0} -ne 0 ]; then \
    userdel -f www-data &&\
    if getent group www-data ; then groupdel www-data; fi &&\
    groupadd -g ${GROUP_ID} www-data &&\
    useradd -l -u ${USER_ID} -g www-data www-data &&\
    install -d -m 0755 -o www-data -g www-data /home/www-data &&\
    chown --changes --silent --no-dereference --recursive \
          --from=33:33 ${USER_ID}:${GROUP_ID} \
        /home/www-data \
;fi


RUN apt-get update \
    && requirements="bash nano curl zlib1g-dev libzip-dev libicu-dev unzip" \
    && apt-get install -y --no-install-recommends $requirements \
  	&& docker-php-ext-install pdo pdo_mysql zip \
    && cd  / && rm -fr /src \
    && apt-get -y update -qq \
    && apt-get -y clean > /dev/null \
    && rm -rf \
        /tmp/* \
        /usr/share/doc/* \
        /var/lib/apt/lists/* \
        /var/tmp/* \
        /var/www/*

#RUN openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/ssl/private/ssl-cert-ekar-test.key -out /etc/ssl/certs/ssl-cert-ekar-test.pem -subj "/C=AT/ST=Vienna/L=Vienna/O=Security/OU=Development/CN=localhost"

RUN mkdir /app

WORKDIR /app

## Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

COPY . /app

USER www-data

EXPOSE 80
EXPOSE 443