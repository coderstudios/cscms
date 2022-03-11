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

use CoderStudios\CsCms\Models\NotificationsRead as Model;
use Illuminate\Contracts\Cache\Factory as Cache;

class NotificationsReadLibrary extends BaseLibrary
{
    public function __construct(Model $model, Cache $cache)
    {
        $this->model = $model;
        $this->cache = $cache->store(config('cache.default'));
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
