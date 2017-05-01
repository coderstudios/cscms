<?php 
/**
 * Part of the CSCMS package by Coder Studios.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the terms of the GNU General Public License v3.
 *
 * @package    CSCMS
 * @version    1.0.0
 * @author     Coder Studios Ltd
 * @license    GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright  (c) 2017, Coder Studios Ltd
 * @link       https://www.coderstudios.com
 */

namespace CoderStudios\CSCMS;

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
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->loadViewsFrom(__DIR__.'/resources/views', 'cscms');

        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'cscms');

        $this->publishes([
            __DIR__.'/resources/lang' => resource_path('lang/vendor/cscms/lang'),
        ], 'lang');

        $this->publishes([
            __DIR__.'/resources/views' => resource_path('views/vendor/cscms'),
        ], 'views');

        $this->publishes([
            __DIR__.'/../../../config/cscms.php' => config_path('cscms.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/resources/assets' => public_path('vendor/cscms'),
        ], 'public');

        $this->app->make('view')->composer('vendor.cscms.frontend.default.layouts.master','CoderStudios\CSCMS\Composers\Frontend\MasterComposer');
        $this->app->make('view')->composer('vendor.cscms.backend.layouts.master','CoderStudios\CSCMS\Composers\Backend\MasterComposer');
	}
}