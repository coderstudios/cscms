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

namespace CoderStudios\CSCMS\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use CoderStudios\CSCMS\Models\Capability;
use Illuminate\Contracts\Cache\Factory as Cache;

class HomeController extends Controller
{
    public function __construct(Cache $cache, Capability $capability)
    {
        $this->capability = $capability;
        $this->cache = $cache->store('backend_views');
    }

	public function index()
	{
        $key = md5(snake_case(str_replace('\\','',__namespace__) . class_basename($this) . '_' .  __function__));
		if ($this->cache->has($key)) {
			$view = $this->cache->get($key);
		} else {
    		$vars = [];
            $view = view('cscms::backend.pages.index', compact('vars'))->render();
            $this->cache->add($key, $view, config('cscms.coderstudios.cache_duration'));
        }
		return $view;
	}

    public function home()
    {
        $key = md5(snake_case(str_replace('\\','',__namespace__) . class_basename($this) . '_' .  __function__));
        if ($this->cache->has($key)) {
            $view = $this->cache->get($key);
        } else {
            $vars = [];
            $view = view('cscms::backend.pages.index', compact('vars'))->render();
            $this->cache->add($key, $view, config('cscms.coderstudios.cache_duration'));
        }
        return $view;
    }

    public function accessDenied()
    {
        $key = md5(snake_case(str_replace('\\','',__namespace__) . class_basename($this) . '_' .  __function__));
        if ($this->cache->has($key)) {
            $view = $this->cache->get($key);
        } else {
            $vars = [];
            $view = view('cscms::backend.pages.access_denied', compact('vars'))->render();
            $this->cache->add($key, $view, config('cscms.coderstudios.cache_duration'));
        }
        return $view;
    }

    public function phpinfo()
    {
        $this->authorize('view_phpinfo',$this->capability->where('name','view_phpinfo')->pluck('id')->first());

        $key = md5(snake_case(str_replace('\\','',__namespace__) . class_basename($this) . '_' .  __function__));
        if ($this->cache->has($key)) {
            $view = $this->cache->get($key);
        } else {
            ob_start();
            phpinfo();
            $phpinfo = ob_get_contents();
            ob_end_clean();

            $phpinfo = preg_replace('#^.*<body>(.*)</body>.*$#s', '$1', $phpinfo);
            $phpinfo = str_replace('module_Zend Optimizer', 'module_Zend_Optimizer', $phpinfo);
            $phpinfo = str_replace('<font', '<span', $phpinfo);
            $phpinfo = str_replace('</font>', '</span>', $phpinfo);
            $phpinfo = str_replace( 'border="0" cellpadding="3"', 'class="table table-bordered table-striped" style="table-layout: fixed;word-wrap: break-word;"', $phpinfo );
            $phpinfo = str_replace('<tr class="h"><th>', '<thead><tr><th>', $phpinfo);
            $phpinfo = str_replace('</th></tr>', '</th></tr></thead><tbody>', $phpinfo);
            $phpinfo = str_replace('</table>', '</tbody></table>', $phpinfo);
            $phpinfo = preg_replace('#>(on|enabled|active)#i', '><span class="text-success">$1</span>', $phpinfo);
            $phpinfo = preg_replace('#>(off|disabled)#i', '><span class="text-error">$1</span>', $phpinfo);
            $vars = [
                'phpinfo' => $phpinfo,
            ];
            $view = view('cscms::backend.pages.phpinfo', compact('vars'))->render();
            $this->cache->add($key, $view, config('cscms.coderstudios.cache_duration'));
        }
        return $view;
    }
}