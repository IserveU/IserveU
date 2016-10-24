#!/usr/bin/env bash
set -x
set -e

sudo apt-get install nginx
sudo cp ./scripts/nginx/nginx.conf.tpl /etc/nginx/nginx.conf
sudo /etc/init.d/nginx restart
