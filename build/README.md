# Build a new app with new version and push it to the repository

Build a new app with new version and push this app to your docker repository.

## Clone the app:

```bash
❯ git clone git@github.com:ixnode/php-json-beautifier.git && \
  cd php-json-beautifier
```

## Run tests

```bash
❯ docker-compose run --rm composer install
❯ docker-compose run --rm composer test
```

## Check the current version of the app:

```bash
# Git version
❯ git describe
v0.4.1

# VERSION file version
❯ cat VERSION
0.4.1

# Console version
❯ bin/console app:version:status
Provider: Shivas\VersioningBundle\Provider\VersionProvider
Formatter: Shivas\VersioningBundle\Formatter\GitDescribeFormatter
Current version: 0.4.1
```

## Change version

Increase the version number according to your needs: `<MAJOR>`.`<MINOR>`.`<PATCH>`

| Name  | Description                                                                               | Command Parameter |
|-------|-------------------------------------------------------------------------------------------|-------------------|
| MAJOR | is increased when API incompatible changes are released.                                  | `--major 1`       |
| MINOR | is increased when new functionality that is compatible with the previous API is released. | `--minor 1`       |
| PATCH | is increased when changes include API-compatible bug fixes only.                          | `--patch 1`       |

* @see: https://semver.org/lang/de/

## Increase the version of the app

```bash
# Switch to main branch
❯ git checkout main && git pull

# Increase patch number by 1
❯ bin/console app:version:bump --patch 1
Provider: Shivas\VersioningBundle\Provider\VersionProvider
Formatter: Shivas\VersioningBundle\Formatter\GitDescribeFormatter
Current version: 0.4.1
New version: 0.4.2

# Show version
❯ cat VERSION
0.4.2

# Push changed VERSION and .env file
❯ git add VERSION .env
❯ git commit -m "Add version $(cat VERSION)"
❯ git push
```

## Tag the app (git)

```bash
# Tag and push new git tag
❯ git tag -a "v$(cat VERSION)" -m "version v$(cat VERSION)"
❯ git push origin "v$(cat VERSION)"
```

## Build the app

```bash
# root web directory
❯ docker build \
  -t ixnode/php-json-beautifier:$(cat VERSION) \
  -t ixnode/php-json-beautifier:latest \
  -f build/Dockerfile \
  --build-arg APP_VERSION=$(cat VERSION) .
❯ docker image ls
```

## Push the app to docker hub (latest):

```bash
❯ docker login -u [username]
❯ docker push ixnode/php-json-beautifier:$(cat VERSION)
❯ docker push ixnode/php-json-beautifier:latest
```

## Start using the build image

### Start containers

```bash
❯ cd build
❯ docker-compose up -d
```

### Open browser

* http://localhost:8000/
