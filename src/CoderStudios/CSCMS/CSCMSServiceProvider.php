<?php 
/**
 * Part of the CSCMS package by Coder Studios.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the terms of the MIT license https://opensource.org/licenses/MIT
 *
 * @package    CSCMS
 * @version    1.0.0
 * @author     Coder Studios Ltd
 * @license    MIT https://opensource.org/licenses/MIT
 * @copyright  (c) 2017, Coder Studios Ltd
 * @link       https://www.coderstudios.com
 */

namespace CoderStudios\CSCMS;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class CSCMSServiceProvider extends ServiceProvider 
{
  	/**
	 * Boot the service provider.
	 *
	 * @return void
	 */
	public function boot()
	{

        $this->registerRoutes();

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->loadViewsFrom(__DIR__.'/resources/views', 'cscms');

        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'cscms');

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

        $this->app->make('view')->composer('cscms::frontend.default.layouts.master','CoderStudios\CSCMS\Composers\Frontend\MasterComposer');
        $this->app->make('view')->composer('cscms::backend.layouts.master','CoderStudios\CSCMS\Composers\Backend\MasterComposer');

        $this->commands([
            Commands\Install::class,
            Commands\Reset::class,
            Commands\Email::class,
            Commands\DBBackup::class,
        ]);
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        if (file_exists(base_path('routes/cscms_backend.php'))) {
            Route::middleware('web')
                ->group(base_path('routes/cscms_backend.php'));
        }

        if (file_exists(base_path('routes/cscms_frontend.php'))) {
            Route::middleware('web')
                 ->group(base_path('routes/cscms_frontend.php'));
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->configure();
    }

    /**
     * Setup the configuration for CSCMS.
     *
     * @return void
     */
    protected function configure()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../../config/cscms.php', 'cscms'
        );
        
        $this->mergeConfigFrom(
            __DIR__.'/../../../config/cache.php', 'cache.stores'
        );
    }
}