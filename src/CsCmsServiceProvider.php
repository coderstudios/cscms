<?php
/**
 * Part of the CsCms package by Coder Studios.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the terms of the MIT license https://opensource.org/licenses/MIT
 *
 * @version    1.0.0
 *
 * @author     Coder Studios Ltd
 * @license    MIT https://opensource.org/licenses/MIT
 * @copyright  (c) 2022, Coder Studios Ltd
 *
 * @see       https://www.coderstudios.com
 */

namespace CoderStudios\CsCms;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class CsCmsServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     */
    public function boot()
    {
        $this->registerRoutes();

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->loadViewsFrom(__DIR__.'/resources/views', 'cscms');

        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'cscms');

        /*

        $this->publishes([
            __DIR__.'/Policies' => app_path('/Policies'),
        ], 'policies');

        $this->publishes([
            __DIR__.'/../../../routes/backend.php' => base_path('/routes/cscms_backend.php'),
        ], 'routes');

        $this->publishes([
            __DIR__.'/../../../routes/frontend.php' => base_path('/routes/cscms_frontend.php'),
        ], 'routes');

        $this->publishes([
            __DIR__.'/database/migrations' => database_path('/migrations'),
        ], 'migrations');

        $this->publishes([
            __DIR__.'/resources/lang' => resource_path('lang/vendor/cscms/lang'),
        ], 'lang');

        $this->publishes([
            __DIR__.'/../../../resources/views' => resource_path('views/vendor/cscms'),
        ], 'views');

        $this->publishes([
            __DIR__.'/../../../config/cscms.php' => config_path('cscms.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../../../resources/assets' => resource_path('vendor/cscms'),
        ], 'resources');

        $this->publishes([
            __DIR__.'/../../../public' => public_path('vendor/cscms'),
        ], 'public');

        $this->publishes([
            __DIR__.'/../../../public/fonts' => public_path('fonts'),
        ], 'fonts');

        $this->app->make('view')->composer('cscms::frontend.default.layouts.master','CoderStudios\CsCms\Composers\Frontend\MasterComposer');
        $this->app->make('view')->composer('cscms::backend.layouts.master','CoderStudios\CsCms\Composers\Backend\MasterComposer');

        */
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        $this->configure();
    }

    /**
     * Register the package routes.
     */
    protected function registerRoutes()
    {
        Route::middleware('web')
            ->prefix(config('cscms.coderstudios.backend_prefix'))
            ->group(__DIR__.'/../routes/backend.php')
        ;

        Route::middleware('web')
            ->prefix(config('cscms.coderstudios.frontend_prefix'))
            ->group(__DIR__.'/../routes/frontend.php')
        ;
    }

    /**
     * Register the Middleware.
     *
     * @param string $middleware
     */
    protected function registerMiddleware($middleware)
    {
        $kernel = $this->app[Kernel::class];
        $kernel->appendMiddlewareToGroup('web', $middleware);
    }

    /**
     * Setup the configuration for CsCms.
     */
    protected function configure()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/cscms.php',
            'cscms'
        );

        $this->commands([
            Commands\Install::class,
            Commands\Update::class,
            Commands\Reset::class,
            Commands\Email::class,
            Commands\DBBackup::class,
        ]);

        $this->registerMiddleware(\CoderStudios\CsCms\Middleware\ClearCache::class);
        $this->registerMiddleware(\CoderStudios\CsCms\Middleware\Notifications::class);
        $this->registerMiddleware(\CoderStudios\CsCms\Middleware\Settings::class);
    }
}
