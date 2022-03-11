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
use CoderStudios\CsCms\Library\Capability as Capabilities;
use CoderStudios\CsCms\Library\Download;
use CoderStudios\CsCms\Library\Email;
use CoderStudios\CsCms\Library\Settings;
use CoderStudios\CsCms\Library\UserRoles;
use CoderStudios\CsCms\Library\Users;
use CoderStudios\CsCms\Models\Capability;
use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function __construct(Request $request, Cache $cache, Capabilities $capabilities, Capability $capability, Settings $settings, Users $users, UserRoles $user_roles, Download $download, Email $emails)
    {
        $this->user = $users;
        $this->email = $emails;
        $this->settings = $settings;
        $this->download = $download;
        $this->user_roles = $user_roles;
        $this->capability = $capability;
        $this->capabilities = $capabilities;
        parent::__construct($cache, $request);
    }

    public function index()
    {
        $this->authorize('view_export', $this->capability->where('name', 'view_export')->pluck('id')->first());
        $key = md5(snake_case(str_replace('\\', '', __NAMESPACE__).class_basename($this).'_'.__FUNCTION__));
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
        $this->authorize('create_export', $this->capability->where('name', 'view_export')->pluck('id')->first());
        $rows = $this->capabilities->getAll();

        return $this->download->getCSV('capabilities-'.date('Ymd').'.csv', $rows->toArray());
    }

    public function settings()
    {
        $this->authorize('create_export', $this->capability->where('name', 'view_export')->pluck('id')->first());
        $rows = $this->settings->getSettings();

        return $this->download->getCSV('settings-'.date('Ymd').'.csv', $rows->toArray());
    }

    public function users()
    {
        $this->authorize('create_export', $this->capability->where('name', 'view_export')->pluck('id')->first());
        $rows = $this->user->getAll();

        return $this->download->getCSV('users-'.date('Ymd').'.csv', $rows->toArray());
    }

    public function userRoles()
    {
        $this->authorize('create_export', $this->capability->where('name', 'view_export')->pluck('id')->first());
        $rows = $this->user_roles->getAll();

        return $this->download->getCSV('user_roles-'.date('Ymd').'.csv', $rows->toArray());
    }

    public function emails()
    {
        $this->authorize('create_export', $this->capability->where('name', 'view_export')->pluck('id')->first());
        $rows = $this->email->getAll();

        return $this->download->getCSV('emails-'.date('Ymd').'.csv', $rows->toArray());
    }
}
