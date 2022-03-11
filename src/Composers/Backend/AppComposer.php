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

namespace CoderStudios\CsCms\Composers\Backend;

use Auth;
use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AppComposer
{
    /*
    |--------------------------------------------------------------------------
    | Admin App Composer Class
    |--------------------------------------------------------------------------
    |
    | Loads variables for the app layout in one place
    |
    */

    public function __construct(Request $request, Cache $cache)
    {
        $this->request = $request;
        $this->backend_cache = $cache->store(config('cache.default'));
    }

    public function compose(View $view)
    {
        $user = Auth::user();
        $view->with('user', $user);
        $view->with('success', '');
        $view->with('error', '');
        $view->with('csrf_error', '');
        $view->with('notifications', '');
        if ($this->request->session) {
            if ($this->request->session()->get('success') || $this->request->session()->get('error') || $this->request->session()->get('csrf_error')) {
                $this->backend_cache->flush();
                $this->request->session()->put('clear_cache', 1);
            }

            $view->with('success', $this->request->session()->pull('success'));
            $view->with('error', $this->request->session()->pull('error'));
            $view->with('csrf_error', $this->request->session()->pull('csrf_error'));
            $notifications = $this->request->session()->pull('notifications');
            $view->with('notifications', $notifications);
        }
    }
}
