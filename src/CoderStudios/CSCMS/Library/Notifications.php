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

namespace CoderStudios\CsCms\Library;

use CoderStudios\CsCms\Models\Notification as Model;
use Illuminate\Contracts\Cache\Factory as Cache;

class Notifications extends BaseLibrary
{
    public function __construct(Model $model, Cache $cache)
    {
        $this->model = $model;
        $this->cache = $cache->store('models');
    }

    public function get($id)
    {
        $key = 'notifications-'.$id;
        if ($this->cache->has($key)) {
            $notification = $this->cache->get($key);
        } else {
            $notification = $this->model->where('id', $id)->first();
            $this->cache->add($key, $notification, config('cscms.coderstudios.cache_duration'));
        }

        return $notification;
    }

    public function getAll($limit = 0, $page = 1)
    {
        $key = md5(snake_case(str_replace('\\', '', __NAMESPACE__).class_basename($this).'_'.__FUNCTION__.'_'.$limit.'_'.$page));
        if ($this->cache->has($key)) {
            $notification = $this->cache->get($key);
        } else {
            $notification = $this->model;
            if (!$limit) {
                $notification_count = $notification->count() > 0 ? $notification->count() : 1;
                $notification = $notification->paginate($notification_count);
            } else {
                $notification = $notification->paginate($limit);
            }
            $this->cache->add($key, $notification, config('cscms.coderstudios.cache_duration'));
        }

        return $notification;
    }

    public function getEnabled($enabled = 1, $limit = 0)
    {
        $key = md5(snake_case(str_replace('\\', '', __NAMESPACE__).class_basename($this).'_'.__FUNCTION__.'_'.$limit.'_'.$enabled));
        if ($this->cache->has($key)) {
            $notification = $this->cache->get($key);
        } else {
            $notification = $this->model->enabled($enabled);
            if (!$limit) {
                $notification_count = $notification->count() > 0 ? $notification->count() : 1;
                $notification = $notification->paginate($notification_count);
            } else {
                $notification = $notification->paginate($limit);
            }
            $this->cache->add($key, $notification, config('cscms.coderstudios.cache_duration'));
        }

        return $notification;
    }
}
