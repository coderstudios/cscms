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

use Artisan;
use CoderStudios\CsCms\Http\Controllers\Controller;
use CoderStudios\CsCms\Library\CapabilityLibrary;
use CoderStudios\CsCms\Library\UtilsLibrary;
use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Http\Request;

class CacheController extends Controller
{
    public function __construct(Request $request, Cache $cache, UtilsLibrary $utils, CapabilityLibrary $capability)
    {
        $this->utils = $utils;
        $this->cache = $cache;
        $this->capability = $capability;
        parent::__construct($cache, $request);
    }

    public function index()
    {
        $this->authorize('view_cache', $this->capability->where('name', 'view_cache')->pluck('id')->first());

        $all_size = 0;
        $all = '0 b';
        if (is_dir($this->cache->getDirectory())) {
            $all_size = $this->utils->getDirectorySize($this->cache->getDirectory());
            $all = $this->utils->convertBytes($this->utils->getDirectorySize($this->cache->getDirectory()));
        }
        $image_size = $this->cacheImageDirSize();
        $vars = [
            'image' => $this->utils->convertBytes($image_size),
            'all' => $this->utils->convertBytes(($all_size + $image_size)),
        ];

        return view('cscms::backend.pages.cache', compact('vars'))->render();
    }

    public function optimiseClasses()
    {
        $this->authorize('update_cache', $this->capability->where('name', 'update_cache')->pluck('id')->first());
        Artisan::call('optimize');

        return redirect()->route('backend.cache')->with('success', 'Classes optimised');
    }

    public function optimiseUrls()
    {
        $this->authorize('update_cache', $this->capability->where('name', 'update_cache')->pluck('id')->first());
        Artisan::call('route:clear');
        Artisan::call('route:cache');

        return redirect()->route('backend.cache')->with('success', 'URLs optimised');
    }

    public function optimiseConfig()
    {
        $this->authorize('update_cache', $this->capability->where('name', 'update_cache')->pluck('id')->first());
        Artisan::call('config:cache');

        return redirect()->route('backend.cache')->with('success', 'Config optimised');
    }

    public function clearFrontend()
    {
        $this->authorize('update_cache', $this->capability->where('name', 'update_cache')->pluck('id')->first());
        $this->frontend_cache->flush();

        return redirect()->route('backend.cache')->with('success', 'Front end cache cleared');
    }

    public function clearBackend()
    {
        $this->authorize('update_cache', $this->capability->where('name', 'update_cache')->pluck('id')->first());
        $this->backend_cache->flush();

        return redirect()->route('backend.cache')->with('success', 'Back end cache cleared');
    }

    public function clearData()
    {
        $this->authorize('update_cache', $this->capability->where('name', 'update_cache')->pluck('id')->first());
        $this->data_cache->flush();

        return redirect()->route('backend.cache')->with('success', 'Data cache cleared');
    }

    public function clearImage()
    {
        $this->authorize('update_cache', $this->capability->where('name', 'update_cache')->pluck('id')->first());
        $this->clearImages();

        return redirect()->route('backend.cache')->with('success', 'Data cache cleared');
    }

    public function clear()
    {
        $this->authorize('update_cache', $this->capability->where('name', 'update_cache')->pluck('id')->first());
        $this->cache->flush();

        return redirect()->route('backend.cache')->with('success', 'All cache cleared');
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
