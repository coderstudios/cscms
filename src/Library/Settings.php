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

use CoderStudios\CsCms\Models\Setting as Model;
use Illuminate\Contracts\Cache\Factory as Cache;

class Settings extends BaseLibrary
{
    public function __construct(Model $model, Cache $cache)
    {
        $this->model = $model;
        $this->cache = $cache->store(config('cache.default'));
    }

    public function getSettings()
    {
        $key = $this->key('settings');
        if ($this->cache->has($key)) {
            $a = $this->cache->get($key);
        } else {
            $config = $this->model->get();
            $a = [];
            foreach ($config as $item) {
                $a[$item->name] = 1 === $item->serialized ? unserialize($item->value) : $item->value;
            }
            $this->cache->add($key, $s, config('cscms.coderstudios.cache_duration'));
        }

        return $a;
    }
}
