CsCms
==========

CsCms is a CMS package in PHP for use with a Laravel project.


[![Latest Stable Version](https://poser.pugx.org/coderstudios/cscms/v/stable)](https://packagist.org/packages/coderstudios/cscms)
[![Total Downloads](https://poser.pugx.org/coderstudios/cscms/downloads)](https://packagist.org/packages/coderstudios/cscms)
[![Latest Unstable Version](https://poser.pugx.org/coderstudios/cscms/v/unstable)](https://packagist.org/packages/coderstudios/cscms)
[![License](https://poser.pugx.org/coderstudios/cscms/license)](https://packagist.org/packages/coderstudios/cscms)

## Composer

To install CsCms as a Composer package to be used with Laravel 5+, simply add this to your composer.json:

```json
"coderstudios/cscms": "1.0.*"
```

..and run `composer update`.

Add the trait UserTraits to your users model. For example

```
<?php

namespace App\Models;

use CoderStudios\CsCms\Traits\UserTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use UserTraits;

```

On a fresh install of laravel run:

1. php artisan vendor:publish --provider="CoderStudios\CsCms\CsCmsServiceProvider"
2. php artisan migrate
3. php artisan cscms:install


If you are developing your own theme, ensure you add the view composer relevant to your theme in the AppServiceProvider.php boot method

```
    view()->composer(config('cscms.coderstudios.theme').'.layouts.app','CoderStudios\CsCms\Composers\Frontend\AppComposer');
```

## Documentation

Once the package is installed you can add

```
    "@php artisan cscms:update"
```

to your composer.json so that on package update, any cached data or views get cleared automatically to account for any new package updates

Example update composer.json file

```
    "@php artisan package:discover",
    "@php artisan cscms:update"

``` 

## Updating

#Assets

php artisan vendor:publish --provider="CoderStudios\CsCms\CsCmsServiceProvider"

php artisan vendor:publish --tag=cscms-public --force

php artisan vendor:publish --tag=cscms-resource --force

php artisan vendor:publish --tag=cscms-config --force

php artisan vendor:publish --tag=cscms-views --force

php artisan vendor:publish --tag=cscms-migrations --force

php artisan vendor:publish --tag=cscms-lang --force

## Copyright and Licence

CsCms has been written by Coder Studios and is released under the MIT License.