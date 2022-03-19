# Setup

## Production

### FTP(S)

1. Add roles and features

<img src="docs/images/1.PNG" alt="1" style="zoom:50%;" />

2. Installation Type `Role-bases`

<img src="docs/images/2.PNG" alt="2" style="zoom:50%;" />

3. Web server (IIS)

<img src="docs/images/3.PNG" alt="3" style="zoom:50%;" />

4. Disable all and enable `FTP`

<img src="docs/images/4.PNG" alt="4" style="zoom:50%;" />

5. `Open Server Manager >Tools > Internet Information Services (IIS) Manager`

<img src="docs/images/5.PNG" alt="5" style="zoom:50%;" />

6. FTP Firewall support

<img src="docs/images/6.PNG" alt="6" style="zoom:50%;" />

7. Set configuration and apply

<img src="docs/images/7.PNG" alt="7" style="zoom:50%;" />

8. Add ports to firewall inbound rules

<img src="docs/images/8.PNG" alt="8" style="zoom:50%;" />

<img src="docs/images/9.PNG" alt="9" style="zoom:50%;" />

<img src="docs/images/10.PNG" alt="10" style="zoom:50%;" />

<img src="docs/images/11.PNG" alt="11" style="zoom:50%;" />

<img src="docs/images/12.PNG" alt="12" style="zoom:50%;" />

9. Create SSL certificate.

```powershell
New-SelfSignedCertificate -FriendlyName "selfsigned-upb-motors" -CertStoreLocation cert:\localmachine\my -DnsName upb-motors.com
```

![13](docs/images/13.PNG)

10. Serve folder in FTP

![14](docs/images/14.PNG)

![15](docs/images/15.PNG)

![16](docs/images/16.PNG)

11. Serve folder in FTPS

![17](docs/images/17.PNG)

![18](docs/images/18.PNG)

![19](docs/images/19.PNG)

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