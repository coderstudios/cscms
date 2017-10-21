CSCMS
==========

CSCMS is a cms package in PHP for use with a Laravel project.


[![Latest Stable Version](https://poser.pugx.org/coderstudios/cscms/v/stable)](https://packagist.org/packages/coderstudios/cscms)
[![Total Downloads](https://poser.pugx.org/coderstudios/cscms/downloads)](https://packagist.org/packages/coderstudios/cscms)
[![Latest Unstable Version](https://poser.pugx.org/coderstudios/cscms/v/unstable)](https://packagist.org/packages/coderstudios/cscms)
[![License](https://poser.pugx.org/coderstudios/cscms/license)](https://packagist.org/packages/coderstudios/cscms)

## Composer

To install CSCMS as a Composer package to be used with Laravel 5+, simply add this to your composer.json:

```json
"coderstudios/cscms": "1.0.*"
```

..and run `composer update`.  Once it's installed, you can register the service provider in `app/config/app.php` in the `providers` array add:

```php
    CoderStudios\CSCMS\CSCMSServiceProvider::class,
```

## Documentation

## Updating

#Assets

php artisan vendor:publish --provider="CoderStudios\CSCMS\CSCMSServiceProvider"

php artisan vendor:publish --tag=public --force

php artisan vendor:publish --tag=resource --force

php artisan vendor:publish --tag=config --force

php artisan vendor:publish --tag=views --force

php artisan vendor:publish --tag=migrations --force

php artisan vendor:publish --tag=lang --force

## Copyright and Licence

CSCMS has been written by Coder Studios and is released under the MIT License.