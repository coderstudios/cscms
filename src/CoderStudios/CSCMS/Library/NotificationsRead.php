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
 
namespace CoderStudios\CSCMS\Library;

use Illuminate\Contracts\Cache\Factory as Cache;
use CoderStudios\Models\NotificationsRead as Model;

class NotificationsRead extends BaseLibrary {

	public function __construct(Model $model, Cache $cache)
	{
		$this->model = $model;
        $this->cache = $cache->store('models');
	}

	public function get($id)
	{
		$key = 'notifications_read-' . $id;
		if ($this->cache->has($key)) {
			$notification = $this->cache->get($key);
		} else {
			$notification = $this->model->where('id',$id)->first();
			$this->cache->add($key, $notification, config('app.coderstudios.cache_duration'));
		}
		return $notification;
	}

	public function hasSeen($user_id = '', $notification_id = '')
	{
		return $this->model
			->where('user_id',$user_id)
			->where('notification_id',$notification_id)
			->count();
	}
}