Getting Started With Chaplean Bundle
====================================

# Prerequisites

###### Fork me !!!!!

# Initialization

## 1. CodeShip

1. Add me on Scrutinizer.
1. Move key deployment  `https://bitbucket.org/chaplean/<bundleName-bundle>/admin/deploy-keys/` in https://bitbucket.org/account/user/chaplean/ssh-keys/ with name 
`CodeShip - chaplean/<bundleName>-bundle`
1. Config global:
``` bash
# Set php version through phpenv. 5.3, 5.4 and 5.5 available
phpenv local 5.5
# Copy files
cp phpunit.xml.dist phpunit.xml
cp app/config/parameters.yml.dist app/config/parameters.yml
# Configuration
echo "memory_limit = 512M" >> ~/.phpenv/versions/5.5/etc/php.ini
echo "xdebug.max_nesting_level = 250" >> ~/.phpenv/versions/5.5/etc/php.ini
# Install dependencies through Composer
composer config -g github-oauth.github.com 7fa26c02ea1b016a956f5d774362096b6bf42d61
composer install --prefer-source --no-interaction
```
2. Pipeline Phpunit:
``` bash
phpunit --coverage-clover build/logs/clover.xml
# PHPUnit and Scruti
wget https://scrutinizer-ci.com/ocular.phar
php ocular.phar code-coverage:upload --access-token="7c2737daabf4aeb9d382cbde4d3a9cfb6a408fa4ec597c2c92c295e4fbbb4cfc" --format=php-clover build/logs/clover.xml
```
2. Pipeline Behat:
``` bash
# Behat
nohup bash -c "java -jar bin/selenium-server-standalone-2.45.0.jar 2>&1 &"
# Launch Web Server
php bin/console server:start localhost:8080
# Test
bin/behat -n
```


## 2. Scrutinizer

1. Add me on Scrutinizer.
1. Move key deployment  `https://bitbucket.org/chaplean/<bundleName-bundle>/admin/deploy-keys/` in https://bitbucket.org/account/user/chaplean/ssh-keys/ with name 
`Scrutinizer - chaplean/<bundleName>-bundle`

## 3. Initialize git-flow

1. Initialize git-flow in repository (cf [Git](https://docs.google.com/document/d/1oBOi_ODucIE0aBGMOnLLTZyzEw0vGT_X1lef0RjJBso/edit))
1. Replace `BundleName` by name of bundle + files:
    * ChapleanBundleNameBundle.php
    * DependencyInjection/ChapleanBundleNameExtension.php
2. Remove index.feature if is useless
2. Rename `chaplean/bundle` in `composer.json` with your bundle name
3. Run `composer install`
3. Run `cp vendor/chaplean/coding-standard/hooks/pre-commit .git/hooks/ && chmod +x .git/hooks/pre-commit`
3. Push ! ;)
