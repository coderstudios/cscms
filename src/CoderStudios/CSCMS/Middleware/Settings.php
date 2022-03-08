<?php
/**
 * Part of the CSCMS package by Coder Studios.
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

namespace CoderStudios\CSCMS\Middleware;

use Closure;
use CoderStudios\CSCMS\Library\Settings as SettingsLibrary;

class Settings
{
    /**
     * Create a new middleware instance.
     *
     * @param \Illuminate\Contracts\Cache\Repository $cache
     */
    public function __construct(SettingsLibrary $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $config = $this->settings->getSettings();
        } catch (\Exception $e) {
            exit('Error. Have you run the migrations yet?');
        }
        if (empty($config)) {
            $config['config_items_per_page'] = 25;
            $config['user_require_username'] = 0;
            $config['user_allow_registration'] = 0;
        }
        $request->config = $config;

        return $next($request);
    }
}
