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

use Auth;
use Artisan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use CoderStudios\Library\Notifications;
use CoderStudios\Library\NotificationsRead;
use CoderStudios\Requests\NotificationRequest;
use Illuminate\Contracts\Cache\Factory as Cache;

class NotificationController extends Controller
{
	public function __construct(Request $request, Cache $cache, Notifications $notifications, NotificationsRead $nr)
    {
    	$this->nr = $nr;
        $this->request = $request;
    	$this->notifications = $notifications;
        $this->cache = $cache->store('backend_views');
        $this->attributes = $this->notifications->getFillable();
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
				'notifications' => $this->notifications->getAll($this->request->session()->get('config')['config_items_per_page'],$page_id),
			];
			$view = view('backend.pages.notifications', compact('vars'))->render();
			$this->cache->add($key, $view, config('app.coderstudios.cache_duration'));
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
				'action'		=> route('backend.notifications.store'),
				'notification'	=> $this->notifications->newInstance(),
			];
			$view = view('backend.pages.notifications-form', compact('vars'))->render();
			$this->cache->add($key, $view, config('app.coderstudios.cache_duration'));
		}
		return $view;
	}

	public function edit($id = '')
	{
		$key = md5(snake_case(str_replace('\\','',__namespace__) . class_basename($this) . '_' .  __function__ . '_' . $id));
		if ($this->cache->has($key) && !count($this->request->session()->get('errors'))) {
			$view = $this->cache->get($key);
		} else {
			$notification = $this->notifications->get($id);
			$vars = [
				'form_type' => 'edit',
				'action' => route('backend.notifications.notification.update', ['id' => $id]),
				'notification' => $notification,
			];
			$view = view('backend.pages.notifications-form', compact('vars'))->render();
			$this->cache->add($key, $view, config('app.coderstudios.cache_duration'));
		}
		return $view;
	}

	public function store(NotificationRequest $request, $id = '')
	{
		$data = $request->only($this->attributes);
		$data['user_id'] = Auth::user()->id;
		$notification = $this->notifications->create($data);
		return redirect()->route('backend.notifications')->with('success_message','Notification created');
	}

	public function update(NotificationRequest $request, $id = '')
	{
		$data = $request->only($this->attributes);
		$data['user_id'] = Auth::user()->id;
		$this->notifications->update($id,$data);
		return redirect()->route('backend.notifications')->with('success_message','Notification updated');
	}

}