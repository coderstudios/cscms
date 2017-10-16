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

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use CoderStudios\Library\Users;
use CoderStudios\Library\UserRoles;
use CoderStudios\Library\Settings;
use CoderStudios\Models\Capability;
use CoderStudios\Library\Capability as Capabilities;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Cache\Factory as Cache;

class ImportController extends Controller
{
	public function __construct(Request $request, Cache $cache, Capabilities $capabilities, Capability $capability, Settings $settings, Users $users, UserRoles $user_roles)
	{
		$this->user = $users;
		$this->request = $request;
		$this->settings = $settings;
		$this->capability = $capability;
		$this->user_roles = $user_roles;
		$this->capabilities = $capabilities;
		$this->cache = $cache->store('backend_views');
	}

	public function index()
	{
		$this->authorize('view_import',$this->capability->where('name','view_export')->pluck('id')->first());
		$key = md5(snake_case(str_replace('\\','',__namespace__) . class_basename($this) . '_' .  __function__));
		if ($this->cache->has($key)) {
			$view = $this->cache->get($key);
		} else {
			$vars = [
				'type' => $this->request->get('type'),
				'replace' => '',
			];
			$view = view('backend.pages.import', compact('vars'))->render();
			$this->cache->add($key, $view, config('app.coderstudios.cache_duration'));
		}
		return $view;
	}

	public function import()
	{
		$this->authorize('create_import',$this->capability->where('name','view_export')->pluck('id')->first());
		$file = $this->request->file('import');
		$contents = file_get_contents($file->getRealPath());
		if ($this->request->get('replace') && strlen($contents)) {
			switch($this->request->get('type')) {
				case 'users':
					$this->user->truncate();
					$this->user->insert($this->convertLines($contents,$this->user->getFillable()));
				break;
				case 'user_roles':
				dd($contents);
					$this->user_roles->truncate();
					$this->user_roles->insert($this->convertLines($contents,$this->user_roles->getFillable()));
				break;
				case 'settings':
					$this->settings->truncate();
					$this->settings->insert($this->convertLines($contents,$this->settings->getFillable()));
				break;
				case 'capabilities':
					$this->capability->truncate();
					$this->capability->insert($this->convertLines($contents,$this->capability->getFillable()));
				break;
			}
		}
		return redirect()->route('backend.index')->with('success_message','Import successful');
	}

	private function convertLines($contents,$columns)
	{
		$temp = [];
		$batch = [];
		foreach(explode("\n",$contents) as $line) {
			$data = explode(",",$line);
			$temp = '';
			for($i=1;$i<count($data);$i++) {
				$temp[$columns[$i-1]] = $data[$i];
				if (isset($temp['created_at'])) {
					$temp['created_at'] = str_replace('"',"",$temp['created_at']);
				}
				if (isset($temp['updated_at'])) {
					$temp['updated_at'] = str_replace('"',"",$temp['updated_at']);
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