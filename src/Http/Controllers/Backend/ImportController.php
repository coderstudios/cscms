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
use CoderStudios\CsCms\Library\CapabilityLibrary;
use CoderStudios\CsCms\Library\SettingsLibrary;
use CoderStudios\CsCms\Library\UserRolesLibrary;
use CoderStudios\CsCms\Library\UsersLibrary;
use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function __construct(Request $request, Cache $cache, CapabilityLibrary $capabilities, CapabilityLibrary $capability, SettingsLibrary $settings, UsersLibrary $users, UserRolesLibrary $user_roles)
    {
        $this->user = $users;
        $this->settings = $settings;
        $this->user_roles = $user_roles;
        $this->capabilities = $capabilities;
        parent::__construct($cache, $request);
    }

    public function index()
    {
        $this->authorize('view_import', $this->capabilities->where('name', 'view_export')->pluck('id')->first());
        $key = $this->key();
        if ($this->useCachedContent($key)) {
            $view = $this->cache->get($key);
        } else {
            $vars = [
                'type' => $this->request->get('type'),
                'replace' => '',
            ];
            $view = view('cscms::backend.pages.import', compact('vars'))->render();
            $this->cache->add($key, $view, config('cscms.coderstudios.cache_duration'));
        }

        return $view;
    }

    public function import()
    {
        $this->authorize('create_import', $this->capabilities->where('name', 'view_export')->pluck('id')->first());
        $file = $this->request->file('import');
        $contents = file_get_contents($file->getRealPath());
        if ($this->request->get('replace') && strlen($contents)) {
            switch ($this->request->get('type')) {
                case 'users':
                    $this->user->truncate();
                    $this->user->insert($this->convertLines($contents, $this->user->getFillable()));

                break;

                case 'user_roles':
                dd($contents);
                    $this->user_roles->truncate();
                    $this->user_roles->insert($this->convertLines($contents, $this->user_roles->getFillable()));

                break;

                case 'settings':
                    $this->settings->truncate();
                    $this->settings->insert($this->convertLines($contents, $this->settings->getFillable()));

                break;

                case 'capabilities':
                    $this->capabilities->truncate();
                    $this->capabilities->insert($this->convertLines($contents, $this->capabilities->getFillable()));

                break;
            }
        }

        return redirect()->route('backend.index')->with('success', 'Import successful');
    }

    private function convertLines($contents, $columns)
    {
        $temp = [];
        $batch = [];
        foreach (explode("\n", $contents) as $line) {
            $data = explode(',', $line);
            $temp = '';
            for ($i = 1; $i < count($data); ++$i) {
                $temp[$columns[$i - 1]] = $data[$i];
                if (isset($temp['created_at'])) {
                    $temp['created_at'] = str_replace('"', '', $temp['created_at']);
                }
                if (isset($temp['updated_at'])) {
                    $temp['updated_at'] = str_replace('"', '', $temp['updated_at']);
                }
                if (empty($temp['updated_at'])) {
                    $temp['updated_at'] = null;
                }
            }
            if (!empty($temp)) {
                $batch[] = $temp;
            }
        }

        return $batch;
    }
}
