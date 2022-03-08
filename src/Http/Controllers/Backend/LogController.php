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

namespace CoderStudios\CsCms\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Cache\Factory as Cache;

class LogController extends Controller
{
    public function __construct(Cache $cache)
    {
        $this->cache = $cache->store(config('cache.default'));
    }

    public function index()
    {
        $key = md5(snake_case(str_replace('\\', '', __NAMESPACE__).class_basename($this).'_'.__FUNCTION__));
        if ($this->cache->has($key)) {
            $view = $this->cache->get($key);
        } else {
            $vars = [
                'log' => file_get_contents(storage_path().'/logs/laravel.log'),
            ];
            $view = view('cscms::backend.pages.log', compact('vars'))->render();
            $this->cache->add($key, $view, config('cscms.coderstudios.cache_duration'));
        }

        return $view;
    }

    public function delete()
    {
        $filename = storage_path().'/logs/laravel.log';
        $handle = fopen($filename, 'r+');
        ftruncate($handle, 0);
        fclose($handle);
        $this->cache->flush();

        return redirect()->route('backend.index')->with('success_message', 'Log file cleared');
    }
}
