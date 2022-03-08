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

use CoderStudios\CsCms\Models\Image as Model;
use Illuminate\Contracts\Cache\Factory as Cache;

class Image extends BaseLibrary
{
    public function __construct(Model $model, Cache $cache)
    {
        $this->model = $model;
        $this->cache = $cache->store('models');
    }

    public function get($id)
    {
        $key = 'image-'.$id;
        if ($this->cache->has($key)) {
            $image = $this->cache->get($key);
        } else {
            $image = $this->model->where('id', $id)->first();
            $this->cache->add($key, $image, config('cscms.coderstudios.cache_duration'));
        }

        return $image;
    }

    public function getAll($params = [])
    {
        $key = md5(snake_case(str_replace('\\', '', __NAMESPACE__).class_basename($this).'_'.__FUNCTION__.'_'.implode($params, '_')));
        if ($this->cache->has($key)) {
            $image = $this->cache->get($key);
        } else {
            $image = $this->model;
            if (isset($params['order'])) {
                $image = $image->orderBy($params['order'], $params['dir']);
            }
            if (!isset($params['size'])) {
                $image_count = $image->count() > 0 ? $image->count() : 1;
                $image = $image->paginate($image_count);
            } else {
                $image = $image->paginate($params['size']);
            }
            $this->cache->add($key, $image, config('cscms.coderstudios.cache_duration'));
        }

        return $image;
    }
}
