<?php
/**
 * Part of the CSCMS package by Coder Studios.
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

use CoderStudios\CsCms\Models\NotificationsRead as Model;
use Illuminate\Contracts\Cache\Factory as Cache;

class NotificationsRead extends BaseLibrary
{
    public function __construct(Model $model, Cache $cache)
    {
        $this->model = $model;
        $this->cache = $cache->store('models');
    }

    public function get($id)
    {
        $key = 'notifications_read-'.$id;
        if ($this->cache->has($key)) {
            $notification = $this->cache->get($key);
        } else {
            $notification = $this->model->where('id', $id)->first();
            $this->cache->add($key, $notification, config('cscms.coderstudios.cache_duration'));
        }

        return $notification;
    }

    public function hasSeen($user_id = '', $notification_id = '')
    {
        return $this->model
            ->where('user_id', $user_id)
            ->where('notification_id', $notification_id)
            ->count()
        ;
    }
}
