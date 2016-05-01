Getting Started With Chaplean Bundle
====================================

# Prerequisites

##### Fork me !!!!!

# Initialization

## 1. Coveralls

1. Add me on Coveralls.
1. Update `.coveralls.yml` file with coveralls token.

## 2. CodeShip

1. Add me on CodeShip.
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
composer install --prefer-dist --no-interaction --dev
```
4. Pipelines for CodeShip: **See Slack**

## 3. Initialize git-flow

1. Initialize git-flow in repository (cf [Git](https://docs.google.com/document/d/1oBOi_ODucIE0aBGMOnLLTZyzEw0vGT_X1lef0RjJBso/edit))
1. Replace `BundleName` by name of bundle + files:
    * ChapleanBundleNameBundle.php
    * DependencyInjection/ChapleanBundleNameExtension.php
2. Rename `chaplean/bundle` in `composer.json` with your bundle name
3. Run `composer install`
3. Run `cp vendor/chaplean/coding-standard/hooks/pre-commit .git/hooks/ && chmod +x .git/hooks/pre-commit`
3. Push ! ;)
