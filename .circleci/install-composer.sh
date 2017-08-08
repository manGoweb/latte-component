#!/usr/bin/env bash
set -euo pipefail
IFS=$'\n\t'

#
# https://discuss.circleci.com/t/composer-not-available-on-php-images/14415/4
#

cd /tmp
EXPECTED_SIGNATURE=$(curl -q https://composer.github.io/installer.sig)
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
ACTUAL_SIGNATURE=$(php -r "echo hash_file('SHA384', 'composer-setup.php');")
if [ "$EXPECTED_SIGNATURE" != "$ACTUAL_SIGNATURE" ]
then
    >&2 echo 'ERROR: Invalid installer signature'
    rm composer-setup.php
    exit 1
fi

sudo php composer-setup.php --quiet --install-dir /usr/local/bin --filename composer
RESULT=$?
rm composer-setup.php
exit $RESULT
