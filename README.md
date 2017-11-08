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

Edit App\Exceptions\Handler.php

..add the use statement 

```
Illuminate\Auth\AuthenticationException;
```

and override the unauthenticated default function with the following

```php

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        $path = route('frontend.login');
        if ($request->is('admin/*') || $request->is('admin')) {
            $path = route('backend.login');
        }
        return $request->expectsJson()
                    ? response()->json(['message' => $exception->getMessage()], 401)
                    : redirect()->guest($path);
    }


```

Update auth.php replace providers array with config:

```
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => CoderStudios\Models\User::class,
        ],

```

On a fresh install of laravel run:

1. php artisan vendor:publish --provider="CoderStudios\CSCMS\CSCMSServiceProvider"
2. php artisan migrate
3. php artisan cscms:install

If the route 

```
Route::get('/', function () {
    return view('welcome');
});
```

exists, remove it as the package provides a route to replace the Laravel default

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