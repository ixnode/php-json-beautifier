# ===========================================
# A Dockerfile to build the app
#
# @author Björn Hempel <bjoern@hempel.li>
# @version 1.0 (2021-10-10)
# ===========================================

# ===========================================
# MIT License
#
# Copyright (c) 2021 Björn Hempel <bjoern@hempel.li>
#
# Permission is hereby granted, free of charge, to any person obtaining a copy
# of this software and associated documentation files (the "Software"), to deal
# in the Software without restriction, including without limitation the rights
# to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
# copies of the Software, and to permit persons to whom the Software is
# furnished to do so, subject to the following conditions:
#
# The above copyright notice and this permission notice shall be included in all
# copies or substantial portions of the Software.
#
# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
# IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
# FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
# AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
# LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
# OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
# SOFTWARE.
# ===========================================

# ===========================================
#
# Check out the app:
# ------------------
# ❯ git clone git@github.com:ixnode/php-json-beautifier.git && \
#   cd php-json-beautifier
#
# Check the current version of the app:
# -------------------------------------
# ❯ git describe
# v0.4.1
# ❯ cat VERSION
# 0.4.1
# ❯ bin/console app:version:status
# Provider: Shivas\VersioningBundle\Provider\VersionProvider
# Formatter: Shivas\VersioningBundle\Formatter\GitDescribeFormatter
# Current version: 0.4.1
#
# Increase the version of the app:
# --------------------------------
# > Increase the version number according to your needs: <MAJOR>.<MINOR>.<PATCH>
# > MAJOR: Is increased when API incompatible changes are released.
# > MINOR: Is increased when new functionality that is compatible with the previous API is released.
# > PATCH: Is increased when changes include API-compatible bug fixes only.
# > @see: https://semver.org/lang/de/
# --------------------------------
# ❯ bin/console app:version:bump --patch 1
# Provider: Shivas\VersioningBundle\Provider\VersionProvider
# Formatter: Shivas\VersioningBundle\Formatter\GitDescribeFormatter
# Current version: 0.4.1
# New version: 0.4.2
# ❯ cat VERSION
# 0.4.1
#
# Tag the app (git):
# ------------------
# ❯ git add VERSION .env
# ❯ git commit -m "Add version $(cat VERSION)"
# ❯ git push
# ❯ git tag -a "v$(cat VERSION)" -m "version v$(cat VERSION)"
# ❯ git push origin "v$(cat VERSION)"
#
# Build the app (latest):
# -----------------------
# # root directory
# ❯ docker build -t ixnode/php-json-beautifier:latest -f build/Dockerfile --build-arg APP_VERSION=$(cat VERSION) .
# ❯ docker image ls
#
# Push the app to docker hub (latest):
# ------------------------------------
# ❯ docker login -u [username]
# ❯ docker push ixnode/php-json-beautifier:latest
#
# Tag latest version - $(cat VERSION):
# ------------------------------------
# ❯ docker tag ixnode/php-json-beautifier:latest ixnode/php-json-beautifier:$(cat VERSION)
# ❯ docker image ls
# ❯ docker push ixnode/php-json-beautifier:$(cat VERSION)
#
# ===========================================

# Use PHP 8.0.11 FPM as data container (needs rw access to data)
# Same version like in ./docker-compose.yml:php:image
FROM php:8.0.11-fpm

# Expect given APP_VERSION when building the app (--build-arg APP_VERSION=1.0.0)
ARG APP_VERSION

# Set app version
ENV APP_VERSION=${APP_VERSION}

# Working dir
WORKDIR /var/www/web

# Add html folder to web folder
COPY . /var/www/web

# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Add configs to nginx
COPY ./docker/php/conf.d/config.ini /usr/local/etc/php/conf.d/config.ini
