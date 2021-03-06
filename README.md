# PHPJsonBeautifier

[![CI workflow](https://github.com/ixnode/php-json-beautifier/actions/workflows/ci-workflow.yml/badge.svg?branch=main)](https://github.com/ixnode/php-json-beautifier/actions/workflows/ci-workflow.yml)
[![Release](https://img.shields.io/github/v/release/ixnode/php-json-beautifier)](https://github.com/ixnode/php-json-beautifier/releases)
[![PHP](https://img.shields.io/badge/PHP-^8.0-777bb3.svg?logo=php&logoColor=white&labelColor=555555&style=flat)](https://www.php.net/supported-versions.php)
[![PHPStan](https://img.shields.io/badge/PHPStan-Level%208-brightgreen.svg?style=flat)](https://phpstan.org/user-guide/rule-levels)
[![PHPStan](https://img.shields.io/badge/PHPCS-PSR12-brightgreen.svg?style=flat)](https://www.php-fig.org/psr/psr-12/)
[![LICENSE](https://img.shields.io/github/license/ixnode/php-json-beautifier)](https://github.com/ixnode/php-json-beautifier/blob/master/LICENSE.md)

An example project that uses the following techniques:

* [Symfony 5.3](https://symfony.com/doc/current/create_framework/introduction.html)
* [PHP 8.0](https://www.php.net/releases/8.0/de.php)
* [PHPUnit 9.5](https://phpunit.readthedocs.io/en/9.5/)
* [PHPStan 0.12](https://phpstan.org/)
* [GitHub](https://github.com/ixnode)
* [Docker Hub](https://hub.docker.com/repository/docker/ixnode/php)
* [CI/CD Integration with GitHub](https://docs.github.com/en/actions/guides/about-continuous-integration)
* [Semantic Versioning 2.0.0](https://semver.org/lang/de/)

## Run the app



### Create a `docker-compose.yml`

Create a file named [`docker-compose.yml`](https://github.com/ixnode/php-json-beautifier/blob/main/build/docker-compose.yml) with the following content:

```yaml
# ===========================================
# ❯ mkdir docker-compose && cd docker-compose
# ❯ vi docker-compose.yml
# ===========================================

version: "3.8"

# Configure services
services:

  # Nginx to serve the app.
  nginx:
    image: "nginx:latest"
    container_name: "de.ixno.php-json-beautifier.nginx"
    hostname: "de-ixno-php-json-beautifier-nginx"
    restart: always
    ports:
      - 8000:80
    volumes:
      # Server static pages (uses the data from php service)
      - data:/var/www/web
      # Add nginx configuration (uses the data from php service)
      - nginx_config:/etc/nginx/conf.d
    depends_on:
      - php
      - composer

  # Use ixnode/php-json-beautifier:latest image (originated from image php:8.0.11-fpm) with the data it contains
  php:
    image: "ixnode/php-json-beautifier:latest"
    container_name: "de.ixno.php-json-beautifier.php"
    hostname: "de-ixno-php-json-beautifier-php"
    restart: always
    volumes:
      # This container shares the folder /var/www/web via volume data, because the container starts
      # first and the content already exists
      - data:/var/www/web
      # This container shares the folder /var/www/web/docker/nginx/conf.d via volume nginx_config, because
      # the container starts first and the content already exists
      - nginx_config:/var/www/web/docker/nginx/conf.d

  # Composer image: This container is executed once and performs a composer install.
  composer:
    image: "composer:latest"
    container_name: "de.ixno.php-json-beautifier.composer"
    hostname: "de-ixno-php-json-beautifier-composer"
    command: ["composer", "install"]
    volumes:
      - data:/app # This container shares the folder /app via volume data, because it already exists

# Configure volumes
volumes:
  data:
    name: "de.ixno.php-json-beautifier.volume.data"
  nginx_config:
    name: "de.ixno.php-json-beautifier.volume.nginx.config"
```

### Create a `.env` file

If you like nicely named projects, use the .env file

```bash
# @see https://docs.docker.com/compose/reference/envvars/#compose_project_name
# ❯ vi docker-compose/.env
COMPOSE_PROJECT_NAME=de-ixno-php-json-beautifier
```

### Start containers

```bash
❯ docker-compose up -d
```

### Open browser

* http://localhost:8000/

### Or use command line

#### Via parameter

```bash
❯ docker-compose exec php bin/console app:json:beautify '{"value": "123"}'
```

```json
{
    "value": "123"
}
```

#### Via STDIN

```bash
❯ echo '{"value": "123"}' | docker-compose exec php bin/console app:json:beautify
```

```json
{
    "value": "123"
}
```

## Other tasks 

* [Build a new app with new version and push it to the repository](build/README.md)