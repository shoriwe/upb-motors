# Setup

## Production

### Docker container

- Update `config.json`:

- Build the image:

```shell
docker build -t upb-motors:latest /path/to/repository
```

- Create the container:

```shell
docker create -p 80:80 --restart unless-stopped --name upb-motors-production upb-motors:latest
```

- Start the container:

```shell
docker start upb-motors-production
```

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