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

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use CoderStudios\CSCMS\Library\Email;
use CoderStudios\CSCMS\Library\Users;
use CoderStudios\CSCMS\Library\UserRoles;
use CoderStudios\CSCMS\Library\Settings;
use CoderStudios\CSCMS\Library\Download;
use CoderStudios\CSCMS\Models\Capability;
use Illuminate\Contracts\Cache\Factory as Cache;
use CoderStudios\CSCMS\Library\Capability as Capabilities;

class ExportController extends Controller
{
	public function __construct(Request $request, Cache $cache, Capabilities $capabilities, Capability $capability, Settings $settings, Users $users, UserRoles $user_roles, Download $download, Email $emails)
	{
		$this->user = $users;
		$this->email = $emails;
		$this->request = $request;
		$this->settings = $settings;
		$this->download = $download;
		$this->user_roles = $user_roles;
		$this->capability = $capability;
		$this->capabilities = $capabilities;
		$this->cache = $cache->store('backend_views');
	}

	public function index()
	{
		$this->authorize('view_export',$this->capability->where('name','view_export')->pluck('id')->first());
		$key = md5(snake_case(str_replace('\\','',__namespace__) . class_basename($this) . '_' .  __function__));
		if ($this->cache->has($key)) {
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
		$this->authorize('create_export',$this->capability->where('name','view_export')->pluck('id')->first());
		$rows = $this->capabilities->getAll();
		return $this->download->getCSV('capabilities-'.date('Ymd').'.csv',$rows->toArray());
	}

	public function settings()
	{
		$this->authorize('create_export',$this->capability->where('name','view_export')->pluck('id')->first());
		$rows = $this->settings->getAll();
		return $this->download->getCSV('settings-'.date('Ymd').'.csv',$rows->toArray());
	}

	public function users()
	{
		$this->authorize('create_export',$this->capability->where('name','view_export')->pluck('id')->first());
		$rows = $this->user->getAll();
		return $this->download->getCSV('users-'.date('Ymd').'.csv',$rows->toArray());
	}

	public function userRoles()
	{
		$this->authorize('create_export',$this->capability->where('name','view_export')->pluck('id')->first());
		$rows = $this->user_roles->getAll();
		return $this->download->getCSV('user_roles-'.date('Ymd').'.csv',$rows->toArray());
	}

	public function emails()
	{
		$this->authorize('create_export',$this->capability->where('name','view_export')->pluck('id')->first());
		$rows = $this->email->getAll();
		return $this->download->getCSV('emails-'.date('Ymd').'.csv',$rows->toArray());
	}
}