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
use Artisan;
use CoderStudios\CsCms\Library\Utils;
use CoderStudios\CsCms\Models\Capability;
use Illuminate\Contracts\Cache\Factory as Cache;

class CacheController extends Controller
{
    public function __construct(Cache $cache, Utils $utils, Capability $capability)
    {
        $this->utils = $utils;
        $this->capability = $capability;
        $this->cache = $cache->store('file');
        $this->data_cache = $cache->store(config('cache.default'));
        $this->backend_cache = $cache->store(config('cache.default'));
        $this->frontend_cache = $cache->store(config('cache.default'));
    }

    public function index()
    {
        $this->authorize('view_cache', $this->capability->where('name', 'view_cache')->pluck('id')->first());

        $all_size = 0;
        $frontend = $backend = $data = $all = '0 b';
        if (is_dir($this->frontend_cache->getDirectory())) {
            $frontend = $this->utils->convertBytes($this->utils->getDirectorySize($this->frontend_cache->getDirectory()));
        }
        if (is_dir($this->backend_cache->getDirectory())) {
            $backend = $this->utils->convertBytes($this->utils->getDirectorySize($this->backend_cache->getDirectory()));
        }
        if (is_dir($this->data_cache->getDirectory())) {
            $data = $this->utils->convertBytes($this->utils->getDirectorySize($this->data_cache->getDirectory()));
        }
        if (is_dir($this->cache->getDirectory())) {
            $all_size = $this->utils->getDirectorySize($this->cache->getDirectory());
            $all = $this->utils->convertBytes($this->utils->getDirectorySize($this->cache->getDirectory()));
        }
        $image_size = $this->cacheImageDirSize();
        $vars = [
            'frontend' => $frontend,
            'backend' => $backend,
            'data' => $data,
            'image' => $this->utils->convertBytes($image_size),
            'all' => $this->utils->convertBytes(($all_size + $image_size)),
        ];

        return view('cscms::backend.pages.cache', compact('vars'))->render();
    }

    public function optimiseClasses()
    {
        $this->authorize('update_cache', $this->capability->where('name', 'update_cache')->pluck('id')->first());
        Artisan::call('optimize');

        return redirect()->route('backend.cache')->with('success_message', 'Classes optimised');
    }

    public function optimiseUrls()
    {
        $this->authorize('update_cache', $this->capability->where('name', 'update_cache')->pluck('id')->first());
        Artisan::call('route:clear');
        Artisan::call('route:cache');

        return redirect()->route('backend.cache')->with('success_message', 'URLs optimised');
    }

    public function optimiseConfig()
    {
        $this->authorize('update_cache', $this->capability->where('name', 'update_cache')->pluck('id')->first());
        Artisan::call('config:cache');

        return redirect()->route('backend.cache')->with('success_message', 'Config optimised');
    }

    public function clearFrontend()
    {
        $this->authorize('update_cache', $this->capability->where('name', 'update_cache')->pluck('id')->first());
        $this->frontend_cache->flush();

        return redirect()->route('backend.cache')->with('success_message', 'Front end cache cleared');
    }

    public function clearBackend()
    {
        $this->authorize('update_cache', $this->capability->where('name', 'update_cache')->pluck('id')->first());
        $this->backend_cache->flush();

        return redirect()->route('backend.cache')->with('success_message', 'Back end cache cleared');
    }

    public function clearData()
    {
        $this->authorize('update_cache', $this->capability->where('name', 'update_cache')->pluck('id')->first());
        $this->data_cache->flush();

        return redirect()->route('backend.cache')->with('success_message', 'Data cache cleared');
    }

    public function clearImage()
    {
        $this->authorize('update_cache', $this->capability->where('name', 'update_cache')->pluck('id')->first());
        $this->clearImages();

        return redirect()->route('backend.cache')->with('success_message', 'Data cache cleared');
    }

    public function clear()
    {
        $this->authorize('update_cache', $this->capability->where('name', 'update_cache')->pluck('id')->first());
        $this->cache->flush();

        return redirect()->route('backend.cache')->with('success_message', 'All cache cleared');
    }

    private function clearImages()
    {
        $dir = storage_path().DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'cache';
        $files = scandir($dir);
        if (count($files)) {
            foreach ($files as $file) {
                if ('.' != $file && '..' != $file) {
                    unlink($dir.DIRECTORY_SEPARATOR.$file);
                }
            }
        }
    }

    private function cacheImageDirSize()
    {
        $dir = storage_path().DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'cache';
        $size = 0;
        if (is_dir($dir)) {
            $files = scandir($dir);
            if (count($files)) {
                foreach ($files as $file) {
                    if ('.' != $file && '..' != $file) {
                        $size = $size + filesize($dir.DIRECTORY_SEPARATOR.$file);
                    }
                }
            }
        }

        return $size;
    }
}
