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
 * @copyright  (c) 2022, Coder Studios Ltd
 * @link       https://www.coderstudios.com
 */

namespace CoderStudios\CSCMS\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use CoderStudios\CSCMS\Library\EmailGroup;
use Illuminate\Contracts\Cache\Factory as Cache;
use CoderStudios\CSCMS\Requests\EmailGroupRequest;

class EmailGroupsController extends Controller
{
	public function __construct(Request $request, Cache $cache, EmailGroup $email_groups)
    {
        $this->request = $request;
		$this->email_group = $email_groups;
        $this->cache = $cache->store('backend_views');
        $this->attributes = $this->email_group->getFillable();
    }

	public function index()
	{
		$page_id = 1;
		if ($this->request->get('page')) {
			$page_id = $this->request->get('page');
		}
		$key = md5(snake_case(str_replace('\\','',__namespace__) . class_basename($this) . '_' .  __function__ . '_' . $page_id));
		if ($this->cache->has($key)) {
			$view = $this->cache->get($key);
		} else {
			$vars = [
				'email_groups' => $this->email_group->getAll($this->request->config['config_items_per_page'],$page_id),
			];
			$view = view('cscms::backend.pages.email_groups', compact('vars'))->render();
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
				'form_type'		=> 'create',
				'action'		=> route('backend.email_groups.store'),
				'email_group'	=> $this->email_group->newInstance(),
			];
			$view = view('cscms::backend.pages.email_groups-form', compact('vars'))->render();
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
			$email_group = $this->email_group->get($id);
			$vars = [
				'form_type'		=> 'edit',
				'action'		=> route('backend.email_groups.email_group.update', ['id' => $id]),
				'email_group'	=> $email_group,
			];
			$view = view('cscms::backend.pages.email_groups-form', compact('vars'))->render();
			$this->cache->add($key, $view, config('cscms.coderstudios.cache_duration'));
		}
		return $view;
	}

	public function store(EmailGroupRequest $request, $id = '')
	{
		$data = $request->only($this->attributes);
		$this->email_group->create($data);
		return redirect()->route('backend.email_groups')->with('success_message','Email Group created');
	}

	public function update(EmailGroupRequest $request, $id = '')
	{
		$data = $request->only($this->attributes);
		$this->email_group->update($id,$data);
		return redirect()->route('backend.email_groups')->with('success_message','Email Group updated');
	}

	public function delete($id)
	{
		$this->email_group->delete($id);
		return redirect()->route('backend.email_groups')->with('success_message','Email Group deleted');
	}
}