Getting Started With Chaplean Bundle
====================================

# Prerequisites

This version of the bundle requires Symfony 3.4+.

# Create my Bundle

1. Fork me !
1. Search **"BundleName"** and replace with bundle name in CamelCase
1. Search **"bundle-name"** and replace with bundle name in lowercase with "-" if necessary
1. Follow steps behind
1. If the bundle needs to be public, you have to remove **private/** folder and **auth.json**

## Initialization

### 1. SSH Key

1. IMPORTANT: replace ssh key (each project needs to have his own key)
1. Generate key without passphrase with `ssh-keygen` in `private/ssh` (replace old ones)
1. Add generate key in Bitbucket with name **"Docker - BundleName Bundle"**
1. Add generate key in ~/.ssh/authorized_key2 on satis.chaplean.coop

## 4. git-flow

1. Initialize git-flow in repository (cf [Git](https://docs.google.com/document/d/1oBOi_ODucIE0aBGMOnLLTZyzEw0vGT_X1lef0RjJBso/edit))
1. Update files name for:
    * ChapleanBundleNameBundle.php
    * DependencyInjection/ChapleanBundleNameExtension.php
1. Rename `chaplean/bundle` in `composer.json` with your bundle name
1. Make `docker exec bundle-name_application composer install`
1. Run `cp vendor/chaplean/coding-standard/hooks/pre-commit .git/hooks/ && chmod +x .git/hooks/pre-commit`

### 5. README.md

Replace the following content with content relative to your bundle.

### 6. Assetic (optionnal)

If you want to use assetic, you can add the bundle in composer and uncomment related lines in `config.yml` and `parameters.yml` files.

# Installation

## 1. Composer

```console
composer require chaplean/bundle-name-bundle
```

## 2. AppKernel.php

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            // ...
            new Chaplean\Bundle\MailerBundle\ChapleanBundleNameBundle(),
        ];

        // ...
    }

    // ...
}
```

## 3. config.yml

##### A. Import

```yaml
    - { resource: '@ChapleanBundleNameBundle/Resources/config/config.yml' }
```

##### B. Configuration

```
chaplean_bundle-name:
    test: false
```
