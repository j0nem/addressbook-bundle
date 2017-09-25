[![GitHub PreRelease](https://img.shields.io/packagist/vpre/j0nem/addressbook-bundle.svg?style=flat-square)](https://packagist.org/packages/j0nem/addressbook-bundle)
[![Packagist Downloads](https://img.shields.io/packagist/dt/j0nem/addressbook-bundle.svg?style=flat-square)](https://packagist.org/packages/j0nem/addressbook-bundle)

# Family Addressbook
An extension for the [Contao Open Source CMS](https://contao.org). Works currently with Contao 4.4.x.

Provides features for a family addressbook website.

# Installation
Install the bundle via Composer:

```
php composer.phar require j0nes/addressbook-bundle
```

Adjust the `AppKernel.php`:

```php
// app/AppKernel.php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            // ...
            new Jmedia\AddressbookBundle\JmediaAddressbookBundle(),
        ];
    }
}
```

Run the contao install tool and update the database.

# Setup
Follow the steps in the backend module "setup wizard".

# Notice
The repository is designed to work for [a specific project](https://familienadressbuch.de).

There is no stable version yet.
