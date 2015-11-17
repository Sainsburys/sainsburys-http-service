#!/bin/bash

clear;
./vendor/bin/phpunit --config tests/phpunit/phpunit.xml;
./vendor/bin/phpspec run --config tests/phpspec/phpspec.yml;
./vendor/bin/behat --config tests/behat/behat.yml --suite servicelevel;

php -S localhost:8081 sample-app/public/index.php &> /dev/null &
WEBSERVER_PROCESS_ID=$!;
./vendor/bin/behat --config tests/behat/behat.yml --suite webserver;
kill -9 $WEBSERVER_PROCESS_ID;
