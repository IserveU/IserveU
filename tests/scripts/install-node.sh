#!/usr/bin/env bash

sudo rm -rf ~/.nvm
curl -sL https://deb.nodesource.com/setup_7.x | sudo -E bash -
sudo apt-get install -y nodejs
node -v