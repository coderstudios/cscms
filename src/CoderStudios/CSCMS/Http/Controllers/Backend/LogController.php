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
use Illuminate\Contracts\Cache\Factory as Cache;

class LogController extends Controller
{
	public function __construct(Cache $cache)
    {
        $this->cache = $cache->store('backend_views');
    }

	public function index()
	{
		$key = md5(snake_case(str_replace('\\','',__namespace__) . class_basename($this) . '_' .  __function__));
		if ($this->cache->has($key)) {
			$view = $this->cache->get($key);
		} else {
			$vars = [
				'log' => file_get_contents(storage_path().'/logs/laravel.log'),
			];
			$view = view('cscms::backend.pages.log', compact('vars'))->render();
			$this->cache->add($key, $view, config('app.coderstudios.cache_duration'));
		}
		return $view;
	}

	public function delete()
	{
		$filename = storage_path().'/logs/laravel.log';
		$handle = fopen($filename, 'r+');
		ftruncate($handle, 0);
		fclose($handle);
		return redirect()->route('backend.index')->with('success_message','Log file cleared');
	}

}