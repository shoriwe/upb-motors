FROM ubuntu:21.10

# Config files
COPY config.json /root/config.json
COPY setup.sh /root/setup.sh
RUN chmod 700 /root/setup.sh
COPY start.sh /root/start.sh
RUN chmod 700 /root/start.sh

# General dependencies

RUN apt update
RUN apt install software-properties-common -y
RUN add-apt-repository ppa:ondrej/php -y
RUN apt-get update

# Setup HTTP and HTTPS servers
RUN apt-get install nginx php8.1 php8.1-fpm php8.1-mysql mariadb-server jq -y

## Database setup
WORKDIR /run/mysqld
RUN chown mysql:mysql /run/mysqld
COPY database/ /root/database


## PHP setup
WORKDIR /run/php
COPY php.ini /etc/php/8.1/fpm/php.ini
RUN php-fpm8.1

## NGINX setup
WORKDIR /var/www/matriz
COPY src/ /var/www/matriz
COPY nginx-http.conf /etc/nginx/sites-available/matriz-http
RUN openssl dhparam -out /etc/ssl/certs/dhparam.pem 2048
COPY ssl-params.conf /etc/nginx/snippets/ssl-params.conf
RUN ln -s /etc/nginx/sites-available/matriz-http /etc/nginx/sites-enabled/matriz-http
RUN rm /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# 
## Setup FTP and FTPS server
#
## Setup DNS server
#RUN apt-get install bind9 dnsutils
#
## Setup Mail server
#RUN apt-get install postfix -y

# Setup script
RUN /root/setup.sh

# Start everything
CMD ["/root/start.sh"]
# Documentation
EXPOSE 80
EXPOSE 443