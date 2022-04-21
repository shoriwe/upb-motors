# Web services

This file is a manual to install the web server in a Debian server.

```shell
cd /opt
git clone https://github.com/shoriwe/upb-motors
cd upb-motors
docker-compose up -d --build
mkdir -p /opt/certs
openssl req -x509 -newkey rsa:4096 -keyout /opt/certs/key.pem -out /opt/certs/cert.pem -sha256 -days 365
```