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

namespace CoderStudios\CSCMS\Composers\Backend;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\Cache\Factory as Cache;

class MasterComposer {

    /*
    |--------------------------------------------------------------------------
    | Admin Master Composer Class
    |--------------------------------------------------------------------------
    |
    | Loads variables for the master layout in one place
    |
    */

    public function __construct(Request $request, Cache $cache)
    {
        $this->request = $request;
        $this->backend_cache = $cache->store('backend_views');
    }

	public function compose(View $view)
	{
        $user = Auth::user();
        $view->with('user',$user);
        $view->with('success_message', '');
        $view->with('error_message', '');
        $view->with('csrf_error', '');
        $view->with('notifications', '');
        if ($this->request->session) {
            if ($this->request->session()->get('success_message') || $this->request->session()->get('error_message') || $this->request->session()->get('csrf_error')) {
                $this->backend_cache->flush();
                $this->request->session()->put('clear_cache',1);
            }

            $view->with('success_message', $this->request->session()->pull('success_message'));
            $view->with('error_message', $this->request->session()->pull('error_message'));
            $view->with('csrf_error', $this->request->session()->pull('csrf_error'));
            $notifications = $this->request->session()->pull('notifications');
            $view->with('notifications', $notifications);
        }
	}
}