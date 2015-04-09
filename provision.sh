#!/bin/bash

# Locales
grep -q 'LC_ALL="en_US.UTF-8"' /etc/environment || echo 'LC_ALL="en_US.UTF-8"' >> /etc/environment

# Bash profile
grep -q "export PATH=\"\$PATH:./vendor/bin:~/.composer/vendor/bin\"" /home/vagrant/.bashrc || echo "export PATH=\"\$PATH:./vendor/bin:~/.composer/vendor/bin\"" >> /home/vagrant/.bashrc

# Updates
apt-get update
apt-get upgrade -y

# Basic packages
apt-get install -y build-essential software-properties-common curl wget vim git

# PHP-FPM
add-apt-repository -y ppa:ondrej/php5
apt-get update
apt-get install -y --force-yes php5-cli php5-sqlite php5-curl php5-gd php5-mcrypt php5-json

sed -i "s/error_reporting = .*/error_reporting = E_ALL/" /etc/php5/cli/php.ini
sed -i "s/display_errors = .*/display_errors = On/" /etc/php5/cli/php.ini
sed -i "s/memory_limit = .*/memory_limit = 512M/" /etc/php5/cli/php.ini
sed -i "s/;date.timezone.*/date.timezone = Europe\/Rome/" /etc/php5/cli/php.ini

# Composer
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer