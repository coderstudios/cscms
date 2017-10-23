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

use Artisan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use CoderStudios\CSCMS\Library\Utils;
use CoderStudios\CSCMS\Models\Capability;
use Illuminate\Contracts\Cache\Factory as Cache;

class BackupsController extends Controller
{
    protected $cache;
    protected $utils;
    protected $keys = [];

	public function __construct(Request $request, Cache $cache, Utils $utils, Capability $capability)
    {
        $this->utils = $utils;
        $this->request = $request;
        $this->capability = $capability;
        $this->cache = $cache->store('backend_views');
    }

	public function index()
	{
		$this->authorize('view_backups',$this->capability->where('name','view_backups')->pluck('id')->first());

		$key = md5(snake_case(str_replace('\\','',__namespace__) . class_basename($this) . '_' .  __function__));
        $this->request->session()->put('key',$key);
		if ($this->cache->has($key)) {
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
		$this->authorize('create_backups',$this->capability->where('name','create_backups')->pluck('id')->first());

        Artisan::call('db:backup');
        $this->cache->forget($this->request->session()->get('key'));
        $this->request->session()->forget('key');
		return redirect()->route('backend.backups')->with('success_message','Backup file generated');
	}

	public function delete()
	{
		$this->authorize('delete_backups',$this->capability->where('name','delete_backups')->pluck('id')->first());

		$id = $this->request->get('id');
		$the_backup = [];
		$backups = $this->utils->getBackUps();
		if(count($backups)) {
			foreach($backups as $backup) {
				if (in_array($id,$backup)) {
					unlink($backup['location']);
				}
			}
		}
		return redirect()->route('backend.backups')->with('success_message','Backup file deleted');
	}

}