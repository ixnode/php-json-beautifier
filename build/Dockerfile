# ===========================================
# A Dockerfile to build the app
#
# @author Björn Hempel <bjoern@hempel.li>
# @version 1.0 (2021-09-24)
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
# Prepare:
# --------
# Remove cache folder if they exists. E.g.: `vendor`, `var/cache/*`, etc.
# ❯ rm -r vendor
# ❯ rm -r var/cache/*
#
# Build the app:
# --------------
# ❯ docker build -t ixnode/php-json-beautifier:0.4.1 -f build/Dockerfile .
# ❯ docker image ls
#
# Push the app to docker hub:
# ---------------------------
# ❯ docker login -u [username]
# ❯ docker push ixnode/php-json-beautifier:0.4.1
#
# Tag latest version:
# -------------------
# ❯ docker tag ixnode/php-json-beautifier:0.4.1 ixnode/php-json-beautifier:latest
# ❯ docker image ls
# ❯ docker push ixnode/php-json-beautifier:latest
#
# ===========================================

# Use PHP 8.0.10 FPM as data container (needs rw access to data)
# Same version like in ./docker-compose.yml:php:image
FROM php:8.0.10-fpm

# Working dir
WORKDIR /var/www/web

# Add html folder to web folder
COPY . /var/www/web

# Add configs to nginx
COPY ./docker/php/conf.d/config.ini /usr/local/etc/php/conf.d/config.ini
