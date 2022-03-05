#!/bin/bash

echo "path[DB_HOST] = $(cat /root/config.json | jq -r '.DB_HOST')" >> /etc/php/8.1/fpm/pool.d/www.conf
echo "path[DB_USERNAME] = $(cat /root/config.json | jq -r '.DB_USERNAME')" >> /etc/php/8.1/fpm/pool.d/www.conf
echo "path[DB_PASSWORD] = $(cat /root/config.json | jq -r '.DB_PASSWORD')" >> /etc/php/8.1/fpm/pool.d/www.conf
echo "path[DB_DATABASE] = $(cat /root/config.json | jq -r '.DB_DATABASE')" >> /etc/php/8.1/fpm/pool.d/www.conf
echo "path[EMAIL_HOST] = $(cat /root/config.json | jq -r '.EMAIL_HOST')" >> /etc/php/8.1/fpm/pool.d/www.conf
echo "path[EMAIL_PORT] = $(cat /root/config.json | jq -r '.EMAIL_PORT')" >> /etc/php/8.1/fpm/pool.d/www.conf
echo "path[EMAIL_USERNAME] = $(cat /root/config.json | jq -r '.EMAIL_USERNAME')" >> /etc/php/8.1/fpm/pool.d/www.conf
echo "path[EMAIL_PASSWORD] = $(cat /root/config.json | jq -r '.EMAIL_PASSWORD')" >> /etc/php/8.1/fpm/pool.d/www.conf

nginx
php-fpm8.1
mysqld  # Maybe execute it normally