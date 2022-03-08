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

use CoderStudios\CsCms\Models\Audit as Model;
use Illuminate\Contracts\Cache\Factory as Cache;

class Audit extends BaseLibrary
{
    public function __construct(Model $model, Cache $cache)
    {
        $this->model = $model;
        $this->cache = $cache->store(config('cache.default'));
    }

    public function get($id)
    {
        $key = 'audit-'.$id;
        if ($this->cache->has($key)) {
            $audit = $this->cache->get($key);
        } else {
            $audit = $this->model->where('id', $id)->first();
            $this->cache->add($key, $audit, config('cscms.coderstudios.cache_duration'));
        }

        return $audit;
    }

    public function getAll($limit = 0, $page = 1)
    {
        $key = md5(snake_case(str_replace('\\', '', __NAMESPACE__).class_basename($this).'_'.__FUNCTION__.'_'.$limit.'_'.$page));
        if ($this->cache->has($key)) {
            $audit = $this->cache->get($key);
        } else {
            $audit = $this->model;
            if (!$limit) {
                $audit_count = $audit->count() > 0 ? $audit->count() : 1;
                $audit = $audit->paginate($audit_count);
            } else {
                $audit = $audit->paginate($limit);
            }
            $this->cache->add($key, $audit, config('cscms.coderstudios.cache_duration'));
        }

        return $audit;
    }

    public function getEnabled($enabled = 1, $limit = 0)
    {
        $key = md5(snake_case(str_replace('\\', '', __NAMESPACE__).class_basename($this).'_'.__FUNCTION__.'_'.$limit.'_'.$enabled));
        if ($this->cache->has($key)) {
            $audit = $this->cache->get($key);
        } else {
            $audit = $this->model->enabled($enabled);
            if (!$limit) {
                $audit_count = $audit->count() > 0 ? $audit->count() : 1;
                $audit = $audit->paginate($audit_count);
            } else {
                $audit = $audit->paginate($limit);
            }
            $this->cache->add($key, $audit, config('cscms.coderstudios.cache_duration'));
        }

        return $audit;
    }
}