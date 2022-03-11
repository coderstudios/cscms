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

use CoderStudios\CsCms\Models\Image as Model;
use Illuminate\Contracts\Cache\Factory as Cache;

class Image extends BaseLibrary
{
    public function __construct(Model $model, Cache $cache)
    {
        $this->model = $model;
        $this->cache = $cache->store(config('cache.default'));
    }

    public function getAll($params = [])
    {
        $key = $this->key(implode($params, '_'));
                if ($this->useCachedContent($key)) {
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
