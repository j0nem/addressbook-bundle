
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

# Notice
The repository is designed to work for [a specific project](https://familienadressbuch.de).
