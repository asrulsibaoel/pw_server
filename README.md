# Docker Debian Apache PHP

**DDAP** is a docker image based on Debian, with Apache 2.4, and PHP (from 7.2 to 8.3).
This image is missing MySQL or any Database on purpose.
[Compose with a Mysql or Maria image to add this feature.](#compose-mysql-image)

> Available on [Docker Hub](https://hub.docker.com/repository/docker/zouloux/docker-debian-apache-php)

Feel free to extend this image and adapt it to your needs or use it directly for your web projects.

> This image is built for `linux/amd64` (x86_64) and `linux/arm64` (aarch64)

> **New** : this image is now from debian-slim, and not from PHP:X-apache anymore.
> It's way slimmer and faster to build. See [./v1](v1) for previous source code.

## Installed extensions

### APT Modules

- all modules installed by default with Debian 11 slim
- gnupg2
- curl
- git
- sendmail
- zip / unzip

### Other utilities

- `composer` ( v2 )

### PHP extensions

- opcache / apcu / memcached
- mysqli / mysql / pdo / pdo-mysql / pdo-sqlite
- zip / bz2
- gd / intl / tokenizer / mcrypt / mbstring
- dom / zimplexml / xml
- curl


> mcrypt is only installed prior to `8.2`, this package being deprecated now.

### PHP

PHP is working in FPM mode which means that a PHP service is running. 

### Apache multi threding

`mpm_event` is installed and configured to allow multi threading of Apache requests.

> `sleep.php` is available in example to check that blocking scripts now works with other requests.

## Usage

Common usage is to use an already built image from Docker Hub :

#### Specify PHP version

```yaml
version: "3.7"
services:
  ddap :
    # Example, we need the legacy PHP 7.4 for a rusty project
    image: zouloux/docker-debian-apache-php:7.4
    volumes:
      - './:/root'
```

> Available versions are `7.2` / `7.3` / `7.4` / `8.0` / `8.1` / `8.2` / `8.3`

> Web root is in `/root/public` by default

## Map volumes

- `/root` is user's root and current directory. Can be used to run processes outside `/public` directory ( ex `composer install` )
- `/root/public` is root published directory by Apache ( http requests starts here ).

```yaml
version: "3.7"
services:
  ddap :
    image: zouloux/docker-debian-apache-php
    volumes:
      - './:/root'
      - './dist:/root/public'
```

## Change default root and public apache directories

```yaml
version: "3.7"
services:
  ddap :
    image: zouloux/docker-debian-apache-php
    volumes:
      - './:/root'
      - './dist:/root/public'
    environment :
      # Custom public and root path
      # Public directory
      DDAP_APACHE_PUBLIC_DIRECTORY_PATH: '/root/admin'
      # Document root.
      # http requests starts here, but the first, but the first public directory is /admin
      DDAP_APACHE_DOCUMENT_ROOT_PATH: '/root'

```


## Enable apache login / password for public directory

This HTTP password will be required on all `/root/public` directory.

```yaml
version: "3.7"
services:
  ddap :
    image: zouloux/docker-debian-apache-php
    environment:
      DDAP_LOGIN: admin
      DDAP_PASSWORD: secret
    volumes:
      - './:/root'
```

## Dev tools

Some devtools can be installed. `http://docker-host/devtools` folder will be available if enabled.
See [/devtools](https://github.com/zouloux/docker-debian-apache-php/tree/main/devtools) directory.

Available devtools :
- apcu cache monitor
- phpinfo

```yaml
version: "3.7"
services:
  ddap :
    image: zouloux/docker-debian-apache-php
    environment:
      DDAP_DEVTOOLS: 'true'
    volumes:
      - './:/root'
```

> You can change the `/devtools` URL with the `DDAP_DEVTOOLS_URL` env. 

> Add your own tools by mapping the `/devtools` volume

## Use dot env file

A `dotenv` file can also be used for convenience.

```yaml
version: "3.7"
services:
  ddap :
    build:
      context: deploy/docker-debian-apache-php
      args:
        IMAGE_PHP_VERSION: ${PHP_VERSION:-}
    environment:
      DDAP_LOGIN: ${DDAP_LOGIN:-}
      DDAP_PASSWORD: ${DDAP_PASSWORD:-}
      DDAP_DEVTOOLS: ${DDAP_DEVTOOLS:-}
      DDAP_DEVTOOLS_URL: ${DDAP_DEVTOOLS_URL:-}
    volumes:
      - './:/root'
```

`.env` file :
```dotenv
PHP_VERSION=8.2
DDAP_LOGIN=admin
DDAP_PASSWORD=secret
DDAP_DEVTOOLS=true
DDAP_DEVTOOLS_URL=/admin/devtools
```

## Customize PHP config

You can override some PHP config with envs.
Here is an override example with default values :

```yaml
services:
  ddap :
    environment:
      DDAP_PHP_TIMEZONE: "UTC"
      DDAP_PHP_MEMORY_LIMIT: "256M"
      DDAP_PHP_MAX_EXECUTION_TIME: "30"
      DDAP_PHP_UPLOAD_SIZE: "128M"
```

## Custom PHP config file

To use custom config files for apache or php,
Simply create your `config/php.ini` for ex, and map specific config file with `volumes`.
Default configs are [available here](https://github.com/zouloux/docker-debian-apache-php/tree/main/config). 

```yaml
version: "3.7"
services:
  ddap:
    image: zouloux/docker-debian-apache-php
    environment:
      DDAP_LOGIN: ${DDAP_LOGIN:-}
      DDAP_PASSWORD: ${DDAP_PASSWORD:-}
      DDAP_DEVTOOLS: ${DDAP_DEVTOOLS:-}
    volumes:
      - './:/root'
      - './config/php.ini:/config/php.ini'
```

> `DDAP_PHP_*` envs will not work anymore with a custom `php.ini` file.

## Compose with MySQL

This image is missing MySQL image on purpose. To add a MySQL server to your stack :

```yaml
version: "3.7"
services:

  ddap :
    image: zouloux/docker-debian-apache-php
    volumes:
      - './:/root'

  maria:
    image: mariadb
    restart: unless-stopped
    container_name: maria
    hostname: maria
    ports:
      - '3306:3306'
    environment:
      MYSQL_ROOT_PASSWORD: "${MYSQL_ROOT_PASSWORD:-}"
    volumes :
      - './data/mysql:/var/lib/mysql:delegated'

  phpmyadmin:
    image: phpmyadmin
    restart: unless-stopped
    container_name: phpmyadmin
    environment:
      PMA_HOST: "maria"
```

---

## Advanced usage

### Test this image or work on it 

- `git clone https://github.com/zouloux/docker-debian-apache-php.git`
- `cd docker-debian-apache-php/test`
- `docker-compose build`
- `docker-compose up`
- Then go to localhost:8080

### Build it locally

If you need to customize this image locally, you still can specify the PHP version.
Use `args` to specify current php version. Non tested PHP version may work.
Docker image needs to be rebuilt if `args` are changed, with `docker-compose build`.

```yaml
version: "3.7"
services:
  ddap :
    build:
      context: path/to/docker-debian-apache-php/trunk
      args:
        IMAGE_PHP_VERSION: 8.2
    volumes:
      - './:/root'
```
