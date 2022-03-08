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

use CoderStudios\CsCms\Models\EmailGroup as Model;
use Illuminate\Contracts\Cache\Factory as Cache;

class EmailGroup extends BaseLibrary
{
    public function __construct(Model $model, Cache $cache)
    {
        $this->model = $model;
        $this->cache = $cache->store(config('cache.default'));
    }

    public function get($id)
    {
        $key = 'email_group-'.$id;
        if ($this->cache->has($key)) {
            $email_group = $this->cache->get($key);
        } else {
            $email_group = $this->model->where('id', $id)->first();
            $this->cache->add($key, $email_group, config('cscms.coderstudios.cache_duration'));
        }

        return $email_group;
    }

    public function getAll($limit = 0, $page = 1)
    {
        $key = md5(snake_case(str_replace('\\', '', __NAMESPACE__).class_basename($this).'_'.__FUNCTION__.'_'.$limit.'_'.$page));
        if ($this->cache->has($key)) {
            $email_group = $this->cache->get($key);
        } else {
            $email_group = $this->model;
            if (!$limit) {
                $email_count = $email_group->count() > 0 ? $email_group->count() : 1;
                $email_group = $email_group->paginate($email_count);
            } else {
                $email_group = $email_group->paginate($limit);
            }
            $this->cache->add($key, $email_group, config('cscms.coderstudios.cache_duration'));
        }

        return $email_group;
    }

    public function getEnabled($enabled = 1, $limit = 0)
    {
        $key = md5(snake_case(str_replace('\\', '', __NAMESPACE__).class_basename($this).'_'.__FUNCTION__.'_'.$limit.'_'.$enabled));
        if ($this->cache->has($key)) {
            $email_group = $this->cache->get($key);
        } else {
            $email_group = $this->model->enabled($enabled);
            if (!$limit) {
                $email_count = $email_group->count() > 0 ? $email_group->count() : 1;
                $email_group = $email_group->paginate($email_count);
            } else {
                $email_group = $email_group->paginate($limit);
            }
            $this->cache->add($key, $email_group, config('cscms.coderstudios.cache_duration'));
        }

        return $email_group;
    }
}
