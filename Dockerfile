FROM ubuntu:21.10

RUN apt update
RUN apt install software-properties-common -y
RUN add-apt-repository ppa:ondrej/php -y
RUN apt-get update

# Setup HTTP and HTTPS servers
RUN apt-get install nginx php8.1 php8.1-fpm php-mysql mariadb-server jq -y

## Database setup
WORKDIR /run/mysqld
RUN chown mysql:mysql /run/mysqld
COPY database/ /root/database
RUN chmod 700 /root/database/setup.sh
RUN /root/database/setup.sh


## PHP setup
WORKDIR /run/php
RUN php-fpm8.1

## NGINX setup
WORKDIR /var/www/matriz
COPY src/ /var/www/matriz
COPY nginx-http.conf /etc/nginx/sites-available/matriz-http
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

# Start everything
COPY php.ini /etc/php/8.1/fpm/php.ini
COPY config.json /root/config.json
COPY start.sh /root/start.sh
RUN chmod 700 /root/start.sh
CMD ["/root/start.sh"]
# Documentation
EXPOSE 80
EXPOSE 443