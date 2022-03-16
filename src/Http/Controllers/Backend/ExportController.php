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
use CoderStudios\CsCms\Library\DownloadLibrary;
use CoderStudios\CsCms\Library\EmailLibrary;
use CoderStudios\CsCms\Library\SettingsLibrary;
use CoderStudios\CsCms\Library\UserRolesLibrary;
use CoderStudios\CsCms\Library\UsersLibrary;
use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function __construct(Request $request, Cache $cache, CapabilityLibrary $capabilities, SettingsLibrary $settings, UsersLibrary $users, UserRolesLibrary $user_roles, DownloadLibrary $download, EmailLibrary $emails)
    {
        $this->user = $users;
        $this->email = $emails;
        $this->settings = $settings;
        $this->download = $download;
        $this->user_roles = $user_roles;
        $this->capabilities = $capabilities;
        parent::__construct($cache, $request);
    }

    public function index()
    {
        $this->authorize('view_export', $this->capabilities->where('name', 'view_export')->pluck('id')->first());
        $key = $this->key();
        if ($this->useCachedContent($key)) {
            $view = $this->cache->get($key);
        } else {
            $vars = [
            ];
            $view = view('cscms::backend.pages.export', compact('vars'))->render();
            $this->cache->add($key, $view, config('cscms.coderstudios.cache_duration'));
        }

        return $view;
    }

    public function capabilities()
    {
        $this->authorize('create_export', $this->capabilities->where('name', 'view_export')->pluck('id')->first());
        $rows = $this->capabilities->get();

        return $this->download->getCSV('capabilities-'.date('Ymd').'.csv', $rows->toArray());
    }

    public function settings()
    {
        $this->authorize('create_export', $this->capabilities->where('name', 'view_export')->pluck('id')->first());
        $rows = $this->settings->getSettings();

        return $this->download->getCSV('settings-'.date('Ymd').'.csv', $rows->toArray());
    }

    public function users()
    {
        $this->authorize('create_export', $this->capabilities->where('name', 'view_export')->pluck('id')->first());
        $rows = $this->user->get();

        return $this->download->getCSV('users-'.date('Ymd').'.csv', $rows->toArray());
    }

    public function userRoles()
    {
        $this->authorize('create_export', $this->capabilities->where('name', 'view_export')->pluck('id')->first());
        $rows = $this->user_roles->get();

        return $this->download->getCSV('user_roles-'.date('Ymd').'.csv', $rows->toArray());
    }

    public function emails()
    {
        $this->authorize('create_export', $this->capabilities->where('name', 'view_export')->pluck('id')->first());
        $rows = $this->email->get();

        return $this->download->getCSV('emails-'.date('Ymd').'.csv', $rows->toArray());
    }
}
