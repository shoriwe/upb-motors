# Setup

## Default Emails

| User       | Domain             |
| ---------- | ------------------ |
| aplicacion | matriz.autoupb.com |
| admin      | matriz.autoupb.com |
| gerente    | matriz.autoupb.com |
| inventario | matriz.autoupb.com |
| rh         | matriz.autoupb.com |
| ventas     | matriz.autoupb.com |

## Production

### Build API

1. Install Go > 1.18.
2. Execute:

```shell
go build -buildvcs=false -ldflags="-s -w" -trimpath -mod vendor -o ./api.exe ./cmd/api
```

### FTP(S)

Using Windows server.

1. Add roles and features

<img src="docs/images/1.png" alt="1" style="zoom:50%;" />

2. Installation Type `Role-bases`

<img src="docs/images/2.png" alt="2" style="zoom:50%;" />

3. Web server (IIS)

<img src="docs/images/3.png" alt="3" style="zoom:50%;" />

4. Disable all and enable `FTP`

<img src="docs/images/4.png" alt="4" style="zoom:50%;" />

5. `Open Server Manager >Tools > Internet Information Services (IIS) Manager`

<img src="docs/images/5.png" alt="5" style="zoom:50%;" />

6. FTP Firewall support

<img src="docs/images/6.png" alt="6" style="zoom:50%;" />

7. Set configuration and apply

<img src="docs/images/7.png" alt="7" style="zoom:50%;" />

8. Add ports to firewall inbound rules

<img src="docs/images/8.png" alt="8" style="zoom:50%;" />

<img src="docs/images/9.png" alt="9" style="zoom:50%;" />

<img src="docs/images/10.png" alt="10" style="zoom:50%;" />

<img src="docs/images/11.png" alt="11" style="zoom:50%;" />

<img src="docs/images/12.png" alt="12" style="zoom:50%;" />

9. Create SSL certificate.

```powershell
New-SelfSignedCertificate -FriendlyName "selfsigned-upb-motors" -CertStoreLocation cert:\localmachine\my -DnsName www.matriz.autoupb.com
```

![13](docs/images/13.png)

10. Serve folder in FTP

<img src="docs/images/14.png" alt="14" style="zoom:50%;" />

<img src="docs/images/15.png" alt="15" style="zoom:50%;" />

<img src="docs/images/16.png" alt="16" style="zoom:50%;" />

11. Serve folder in FTPS

<img src="docs/images/17.png" alt="17" style="zoom:50%;" />

<img src="docs/images/18.png" alt="18" style="zoom:50%;" />

<img src="docs/images/19.png" alt="19" style="zoom:50%;" />

### HTTP(S)

#### Setup container

Install docker in the operating system.

1. Update `config.json`:

2. Build the image:

```shell
docker build -t upb-motors:latest /path/to/repository
```

3. Create the container:

```shell
docker create -p 80:80 -p 443:443 --restart unless-stopped --name upb-motors-production upb-motors:latest
```

4. Start the container:

```shell
docker start upb-motors-production
```

### Primary DNS

#### Using Windows Server.

1. Add roles and features

<img src="docs/images/1.png" alt="1" style="zoom:50%;" />

2. Installation Type `Role-bases`

<img src="docs/images/2.png" alt="2" style="zoom:50%;" />

3. DNS server

<img src="docs/images/21.png" alt="21" style="zoom:50%;" />

4. Confirmation and install

<img src="docs/images/22.png" alt="22" style="zoom:50%;" />

4. `Open Server Manager >Tools > DNS`

<img src="docs/images/23.png" alt="23" style="zoom:50%;" />

5. Select server

<img src="docs/images/24.png" alt="24" style="zoom:50%;" />

6. New zone

![25](docs/images/25.png)

7. Primary zone

<img src="docs/images/26.png" alt="26" style="zoom:50%;" />

8. Forward lookup zone

![27](docs/images/27.png)

9. Zone name

<img src="docs/images/28.png" alt="28" style="zoom:50%;" />

10. Zone file

<img src="docs/images/29.png" alt="29" style="zoom:50%;" />

11. No Dynamic updates

<img src="docs/images/30.png" alt="30" style="zoom:50%;" />

12. New Reverse lookup zone

<img src="docs/images/35.png" alt="35" style="zoom:50%;" />

<img src="docs/images/36.png" alt="36" style="zoom:50%;" />

<img src="docs/images/37.png" alt="37" style="zoom: 50%;" />

13. Ensure zone transfer is allowed (`Zone properties -> Zone tranfers`):

<img src="docs/images/45.png" alt="45" style="zoom:50%;" />

##### Services Domains

###### HTTP/HTTPS

1. New Host **A**

<img src="docs/images/31.png" alt="31" style="zoom:50%;" />

2. `www` name

<img src="docs/images/32.png" alt="32" style="zoom:50%;" />

###### FTP/FTPS

1. `ftp` name (A)

<img src="docs/images/33.png" alt="33" style="zoom:50%;" />

###### All mail services

1. `mail` host (A)

<img src="docs/images/39.png" alt="40" style="zoom:50%;" />

2. Mail exchange (MX)

<img src="docs/images/40.png" alt="40" style="zoom:50%;" />

### Secondary DNS

For each company, create a zone pointing to its domain and domain server. (Ensure that zone transfer is allowed in the primary server).

1. Create zone

<img src="docs/images/41.png" alt="41" style="zoom:50%;" />

2. Point to domain of company

<img src="docs/images/42.png" alt="42" style="zoom:50%;" />

<img src="docs/images/43.png" alt="43" style="zoom:50%;" />

<img src="docs/images/44.png" alt="44" style="zoom:50%;" />



### Mail

1. Access the file `sources.list`

```shell
sudo nano sources.list
```

2. comment the second line

![46](docs/images/46.png)

3. update repositories

```shell
sudo apt update
```

4. install postfix

```shell
apt install postfix
```

5. Press accept in postfix configuration

![47](docs/images/47.png)

6. Select website

![48](docs/images/48.png)

7. Add a mail system name

![49](docs/images/49.png)

8. restart postfix

```shell
sudo service postfix restart
```

9. Install dovecot

```shell
sudo apt install dovecot-imapd dovecot-pop3d
```

10. restart dovecot

```shell
sudo service dovecot restart
```

11. install PHP

```shell
sudo apt-get install php
```

12. install squirrelmail

```shell
wget https://sourceforge.net/projects/squirrelmail/files/stable/1.4.22/squirrelmail-webmail-1.4.22.zip
```

13. unzip the file

```shell
unzip squirrelmail-webmail-1.4.22.zip
```

14. Select file location

```shell
sudo mv squirrelmail-webmail-1.4.22 /var/www/html/
```

15. Change Directory Owner

```shell
sudo chown -R www-data:www-data /var/www/html/squirrelmail-webmail-1.4.22/
sudo chmod 755 -R /var/www/html/squirrelmail-webmail-1.4.22/
sudo mv /var/www/html/squirrelmail-webmail-1.4.22/ /var/www/html/squirrelmail
```

16. configure squirrelmail

```shell
sudo perl /var/www/html/squirrelmail/config/conf.pl
```

17. Select option 2 of "server settings"

![50](docs/images/50.png)

18. Select option 1 of "domain"

![51](docs/images/51.png)

19. Write the domain

![52](docs/images/52.png)

20. Select option 4 of "general options"

![53](docs/images/53.png)

21. Modify options 1,2 and 11

![54](docs/images/54.png)

22. Enter the link `localhost/src/login.php`

![55](docs/images/55.png)

## Development

### `php.ini`

```ini
extension = /path/to/php_openssl
extension = /path/to/php_pdo_mysql
file_uploads = On
upload_max_filesize = 100M
post_max_size = 100M
```

## Environment variables for php process

```ini
DB_HOST = HOST:PORT
DB_USERNAME = USERNAME
DB_PASSWORD = PASSWORD
DB_DATABASE = DATABASE
EMAIL_HOST = HOST
EMAIL_PORT = PORT
EMAIL_USERNAME = USERNAME
EMAIL_PASSWORD = PASSWORD
```