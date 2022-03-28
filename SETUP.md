# Setup

## IPs and Domains

| Service             | OS      | Domain            | Running IP      |
| ------------------- | ------- | ----------------- | --------------- |
| FTP/FTPS            | Windows | ftp.upb-motors.co | 192.168.101.50  |
| Primary DNS         | Windows |                   | 192.168.101.50  |
| All email services? | Debian? |                   | 192.168.101.58? |
| HTTP/HTTPS          | Debian  | www.upb-motors.co | 192.168.101.58  |
| VoIP                | Debian? |                   | 192.168.101.58  |

## Default Emails

| User       | Domain        |
| ---------- | ------------- |
| aplicacion | upb-motors.co |
| admin      | upb-motors.co |
| gerente    | upb-motors.co |
| inventario | upb-motors.co |
| rh         | upb-motors.co |
| ventas     | upb-motors.co |

## Production

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
New-SelfSignedCertificate -FriendlyName "selfsigned-upb-motors" -CertStoreLocation cert:\localmachine\my -DnsName upb-motors.com
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

Using Windows Server.

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

#### Services Domains

##### HTTP/HTTPS

1. New Host **A**

<img src="docs/images/31.png" alt="31" style="zoom:50%;" />

2. `www` name

<img src="docs/images/32.png" alt="32" style="zoom:50%;" />

##### FTP/FTPS

1. `ftp` name

![33](docs/images/33.png)

##### All mail services

### Secondary DNS



### Mail

1. Install `.NET framework 3.5 (includes .NET 2.0 and 3.0)` feature
2. Install `hMailServer`

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