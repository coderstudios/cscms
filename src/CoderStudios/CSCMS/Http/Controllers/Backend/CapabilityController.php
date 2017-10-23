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
use CoderStudios\CSCMS\Library\Capability;
use CoderStudios\CSCMS\Requests\CapabilityRequest;
use Illuminate\Contracts\Cache\Factory as Cache;

class CapabilityController extends Controller
{

	public function __construct(Request $request, Cache $cache, Capability $capability)
    {
        $this->request = $request;
        $this->capability = $capability;
        $this->cache = $cache->store('backend_views');
        $this->attributes = $this->capability->getFillable();
    }

	public function index()
	{
		/*
		$capabilities = [
			'settings',
			'cache',
		];

		$count = 2;
		foreach($capabilities as $item) {
			$data = [
				'name' => 'view_' . $item,
				'enabled' => 1,
				'sort_order' => $count,
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			];
			$this->capability->create($data);
			$data = [
				'name' => 'create_' . $item,
				'enabled' => 1,
				'sort_order' => $count,
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			];
			$this->capability->create($data);
			$data = [
				'name' => 'edit_' . $item,
				'enabled' => 1,
				'sort_order' => $count,
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			];
			$this->capability->create($data);
			$data = [
				'name' => 'delete_' . $item,
				'enabled' => 1,
				'sort_order' => $count,
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			];
			$this->capability->create($data);
			$count++;
		}*/

		$page_id = 1;
		if ($this->request->get('page')) {
			$page_id = $this->request->get('page');
		}
		$key = md5(snake_case(str_replace('\\','',__namespace__) . class_basename($this) . '_' .  __function__ . '_' . $page_id));
		if ($this->cache->has($key)) {
			$view = $this->cache->get($key);
		} else {
			$vars = [
				'capabilities' => $this->capability->getAll($this->request->session()->get('config')['config_items_per_page'],$page_id),
			];
			$view = view('cscms::backend.pages.capabilities', compact('vars'))->render();
			$this->cache->add($key, $view, config('cscms.coderstudios.cache_duration'));
		}
		return $view;
	}

	public function create()
	{
		$key = md5(snake_case(str_replace('\\','',__namespace__) . class_basename($this) . '_' .  __function__));
		if ($this->cache->has($key) && !count($this->request->session()->get('errors'))) {
			$view = $this->cache->get($key);
		} else {
			$vars = [
				'id' 		=> '',
				'form_type' => 'create',
				'action' 	=> route('backend.capabilities.store'),
				'capability' => $this->capability->newInstance(),
			];
			$view = view('cscms::backend.pages.capabilities-form', compact('vars'))->render();
			$this->cache->add($key, $view, config('cscms.coderstudios.cache_duration'));
		}
		return $view;
	}

	public function edit($id = '')
	{
		$key = md5(snake_case(str_replace('\\','',__namespace__) . class_basename($this) . '_' .  __function__ . '_' . $id));
		if ($this->cache->has($key) && !count($this->request->session()->get('errors'))) {
			$view = $this->cache->get($key);
		} else {
			$vars = [
				'id' 		=> $id,
				'form_type' => 'edit',
				'action' 	=> route('backend.capabilities.capability.update',['id' => $id]),
				'capability' => $this->capability->get($id),
			];
			$view = view('cscms::backend.pages.capabilities-form', compact('vars'))->render();
			$this->cache->add($key, $view, config('cscms.coderstudios.cache_duration'));
		}
		return $view;
	}

	public function store(CapabilityRequest $request)
	{
		$data = $request->only($this->attributes);
		$data['created_at'] = date('Y-m-d H:i:s');
		$this->capability->create($data);
		return redirect()->route('backend.capabilities')->with('success_message','Capability created');
	}

	public function update(CapabilityRequest $request, $id)
	{
		$data = $request->only($this->attributes);
		if (empty($data['enabled'])) {
			$data['enabled'] = 0;
		}
		$data['updated_at'] = date('Y-m-d H:i:s');
		$this->capability->update($id,$data);
		return redirect()->route('backend.capabilities')->with('success_message','Capability updated');
	}

	public function delete($id)
	{
		$this->capability->delete($id);
		return redirect()->route('backend.capabilities')->with('success_message','Capability deleted');
	}
}