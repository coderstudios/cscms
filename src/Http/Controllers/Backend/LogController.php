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

use CoderStudios\CsCms\Http\Controllers\Controller;
use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function __construct(Request $request, Cache $cache)
    {
        parent::__construct($cache, $request);
    }

    public function index()
    {
        $key = $this->key();
        if ($this->useCachedContent($key)) {
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

        return redirect()->route('backend.index')->with('success', 'Log file cleared');
    }
}
