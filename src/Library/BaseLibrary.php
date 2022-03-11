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

use CoderStudios\CsCms\Traits\CachedContent;
use CoderStudios\CsCms\Traits\GenerateKey;

class BaseLibrary
{
    use GenerateKey;
    use CachedContent;

    protected $cache;

    protected $model;

    public function __call($method, $args)
    {
        return call_user_func_array([$this->model, $method], $args);
    }

    public function newInstance()
    {
        return $this->model->newInstance();
    }

    public function create($data)
    {
        $this->cache->flush();

        return $this->model->create($data);
    }

    public function update($id, $data)
    {
        $this->cache->flush();

        return $this->model->where('id', $id)->update($data);
    }

    public function delete($id)
    {
        $this->cache->flush();

        return $this->model->where('id', $id)->delete();
    }

    public function get($id)
    {
        $key = $this->key(class_basename($this).'-'.$id);
        if ($this->useCachedContent($key)) {
            $item = $this->cache->get($key);
        } else {
            $item = $this->model->where('id', $id)->first();
            $this->cache->add($key, $item, config('cscms.coderstudios.cache_duration'));
        }

        return $item;
    }

    public function getEnabled($enabled = 1, $limit = 0)
    {
        $key = $this->key($limit.'_'.$enabled);
        if ($this->useCachedContent($key)) {
            $item = $this->cache->get($key);
        } else {
            $audit = $this->model->enabled($enabled);
            if (!$limit) {
                $count = $item->count() > 0 ? $item->count() : 1;
                $item = $item->paginate($count);
            } else {
                $item = $item->paginate($limit);
            }
            $this->cache->add($key, $item, config('cscms.coderstudios.cache_duration'));
        }

        return $item;
    }
}
