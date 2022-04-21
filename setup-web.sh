#!/bin/bash

cd /opt
git clone https://github.com/shoriwe/upb-motors
cd upb-motors
docker-compose up -d --build
mkdir -p /opt/certs
openssl req -x509 -newkey rsa:4096 -keyout /opt/certs/key.pem -out /opt/certs/cert.pem -sha256 -days 365
cp nginx.conf /etc/nginx/sites-available/upb-motors
cd /etc/nginx/sites-enabled/
ln -s /etc/nginx/sites-enabled/upb-motors upb-motors
systemctl restart nginx