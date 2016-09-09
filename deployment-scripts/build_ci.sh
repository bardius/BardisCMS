#!/usr/bin/env bash
# This script will
# install the dependencies
# clear the Symfony2 cache
# generate optimized autoload

# 1 - Location of the build checkout - double quoted, no trailing slash
# 2 - Relative path to be build if any - with trailing slash


# building the unix path for the root directory
dirRoot=$1
echo -e "\n\n\e[0;34mUnix path for Jenkins Workspace working dir is:\e[0m" $dirRoot

echo -e "\n\n\e[0;34m********** Setting up the correct composer.json file **********\e[0m"
echo sudo cp -v $dirRoot/$2composer.json.jenkins $dirRoot/$2composer.json
sudo cp -v $dirRoot/$2composer.json.jenkins $dirRoot/$2composer.json

echo -e "\n\n\e[0;34m********** Updating Composer Dependensies **********\e[0m"
echo sudo php -dmemory_limit=750M $dirRoot/$2composer.phar install -o
sudo php -dmemory_limit=750M $dirRoot/$2composer.phar install -o

echo -e "\n\n\e[0;34m********** Clear Cache **********\e[0m"
echo sudo php $dirRoot/$2app/console cache:clear --no-debug
sudo php $dirRoot/$2app/console cache:clear --no-debug

echo sudo php $dirRoot/$2app/console cache:clear --env=prod --no-debug
sudo php $dirRoot/$2app/console cache:clear --env=prod --no-debug

echo -e "\n\n\e[0;34m********** Generate optimized autoload **********\e[0m"
echo sudo php $dirRoot/$2composer.phar dumpautoload -o
sudo php $dirRoot/$2composer.phar dumpautoload -o
