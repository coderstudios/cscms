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

use CoderStudios\CsCms\Models\Upload as Model;
use Illuminate\Contracts\Cache\Factory as Cache;

class Upload extends BaseLibrary
{
    public function __construct(Model $model, Cache $cache)
    {
        $this->model = $model;
        $this->cache = $cache->store(config('cache.default'));
    }

    public function get($id)
    {
        $key = 'upload-'.$id;
        if ($this->cache->has($key)) {
            $upload = $this->cache->get($key);
        } else {
            $upload = $this->model->where('id', $id)->first();
            $this->cache->add($key, $upload, config('cscms.coderstudios.cache_duration'));
        }

        return $upload;
    }

    public function getAll($limit = 0, $page = 1)
    {
        $upload = $this->model;
        $key = md5(snake_case(str_replace('\\', '', __NAMESPACE__).class_basename($this).'_'.__FUNCTION__.'_'.$limit.'_'.$page));
        if ($this->cache->has($key)) {
            $upload = $this->cache->get($key);
        } else {
            if (!$limit) {
                $upload_count = $upload->count() > 0 ? $upload->count() : 1;
                $upload = $upload->paginate($upload_count);
            } else {
                $upload = $upload->paginate($limit);
            }
            $this->cache->add($key, $upload, config('cscms.coderstudios.cache_duration'));
        }

        return $upload;
    }
}
