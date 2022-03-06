#!/bin/bash

echo "ssl_certificate /etc/ssl/certs/nginx-selfsigned.crt;" > /etc/nginx/snippets/self-signed.conf
echo "ssl_certificate_key /etc/ssl/private/nginx-selfsigned.key;" >> /etc/nginx/snippets/self-signed.conf
echo -e "CO\nSantander\n\n\n\n\n\n" | openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/ssl/private/nginx-selfsigned.key -out /etc/ssl/certs/nginx-selfsigned.crt

echo "env[DB_HOST]=$(cat /root/config.json | jq -r '.DB_HOST')" >> /etc/php/8.1/fpm/pool.d/www.conf
echo "env[DB_USERNAME]=$(cat /root/config.json | jq -r '.DB_USERNAME')" >> /etc/php/8.1/fpm/pool.d/www.conf
echo "env[DB_PASSWORD]=$(cat /root/config.json | jq -r '.DB_PASSWORD')" >> /etc/php/8.1/fpm/pool.d/www.conf
echo "env[DB_DATABASE]=$(cat /root/config.json | jq -r '.DB_DATABASE')" >> /etc/php/8.1/fpm/pool.d/www.conf
echo "env[EMAIL_HOST]=$(cat /root/config.json | jq -r '.EMAIL_HOST')" >> /etc/php/8.1/fpm/pool.d/www.conf
echo "env[EMAIL_PORT]=$(cat /root/config.json | jq -r '.EMAIL_PORT')" >> /etc/php/8.1/fpm/pool.d/www.conf
echo "env[EMAIL_USERNAME]=$(cat /root/config.json | jq -r '.EMAIL_USERNAME')" >> /etc/php/8.1/fpm/pool.d/www.conf
echo "env[EMAIL_PASSWORD]=$(cat /root/config.json | jq -r '.EMAIL_PASSWORD')" >> /etc/php/8.1/fpm/pool.d/www.conf

mysqld &
sleep 5
mysql --force < /root/database/setup.sql
mysql --force < /root/database/client-user.sql
if [ "$(cat /root/config.json | jq -r '.TEST')" -eq "1" ]; then
  mysql --force < /root/database/test-data.sql
fi