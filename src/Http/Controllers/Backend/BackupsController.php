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
use CoderStudios\CsCms\Library\Utils;
use CoderStudios\CsCms\Models\Capability;
use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Http\Request;

class BackupsController extends Controller
{
    protected $cache;
    protected $utils;
    protected $keys = [];

    public function __construct(Request $request, Cache $cache, Utils $utils, Capability $capability)
    {
        $this->utils = $utils;
        $this->capability = $capability;
        parent::__construct($cache, $request);
    }

    public function index()
    {
        $this->authorize('view_backups', $this->capability->where('name', 'view_backups')->pluck('id')->first());

        $key = md5(snake_case(str_replace('\\', '', __NAMESPACE__).class_basename($this).'_'.__FUNCTION__));
        $this->request->session()->put('key', $key);
        if ($this->useCachedContent($key)) {
            $view = $this->cache->get($key);
        } else {
            $backups = $this->utils->getBackUps();
            $vars = [
                'backups' => $backups,
            ];
            $view = view('cscms::backend.pages.backups', compact('vars'))->render();
            $this->cache->add($key, $view, config('cscms.coderstudios.cache_duration'));
        }

        return $view;
    }

    public function backup()
    {
        $this->authorize('create_backups', $this->capability->where('name', 'create_backups')->pluck('id')->first());

        Artisan::call('db:backup');
        $this->cache->forget($this->request->session()->get('key'));
        $this->request->session()->forget('key');

        return redirect()->route('backend.backups')->with('success_message', 'Backup file generated');
    }

    public function delete()
    {
        $this->authorize('delete_backups', $this->capability->where('name', 'delete_backups')->pluck('id')->first());

        $id = $this->request->get('id');
        $the_backup = [];
        $backups = $this->utils->getBackUps();
        if (count($backups)) {
            foreach ($backups as $backup) {
                if (in_array($id, $backup)) {
                    unlink($backup['location']);
                }
            }
        }

        return redirect()->route('backend.backups')->with('success_message', 'Backup file deleted');
    }
}
