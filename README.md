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

..and run `composer update`.  Once it's installed, you can register the service providers in `app/config/app.php` in the `providers` array add:

```php
    CoderStudios\CSCMS\CSCMSServiceProvider::class,
    CoderStudios\CSCMS\Policies\PolicyProvider::class,
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
            'model' => CoderStudios\CSCMS\Models\User::class,
        ],

```

Update Kernel.php (app/Http/Kernel.php) and add the following middleware:

```

        'cache' => \CoderStudios\CSCMS\Middleware\ClearCache::class,
        'notifications' => \CoderStudios\CSCMS\Middleware\Notifications::class,
        'settings' => \CoderStudios\CSCMS\Middleware\Settings::class,

```

So it would similar too:

```
    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,

        'cache' => \CoderStudios\CSCMS\Middleware\ClearCache::class,
        'notifications' => \CoderStudios\CSCMS\Middleware\Notifications::class,
        'settings' => \CoderStudios\CSCMS\Middleware\Settings::class,

    ];


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

If you are developing your own theme, ensure you add the view composer relevant to your theme in the AppServiceProvider.php boot method

```
    view()->composer(config('cscms.coderstudios.theme').'.layouts.master','CoderStudios\CSCMS\Composers\Frontend\MasterComposer');
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

php artisan vendor:publish --provider="CoderStudios\CSCMS\CSCMSServiceProvider"

php artisan vendor:publish --tag=public --force

php artisan vendor:publish --tag=resource --force

php artisan vendor:publish --tag=config --force

php artisan vendor:publish --tag=views --force

php artisan vendor:publish --tag=migrations --force

php artisan vendor:publish --tag=lang --force

## Copyright and Licence

CSCMS has been written by Coder Studios and is released under the MIT License.