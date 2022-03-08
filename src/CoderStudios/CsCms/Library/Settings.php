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
        $this->cache = $cache->store('models');
    }

    public function getSettings()
    {
        $config = $this->getAll();
        $a = [];
        foreach ($config as $item) {
            $a[$item->name] = 1 === $item->serialized ? unserialize($item->value) : $item->value;
        }

        return $a;
    }

    public function get($id)
    {
        $key = 'setting-'.$id;
        if ($this->cache->has($key)) {
            $setting = $this->cache->get($key);
        } else {
            $setting = $this->model->where('id', $id)->first();
            $this->cache->add($key, $setting, config('cscms.coderstudios.cache_duration'));
        }

        return $setting;
    }

    public function getAll($limit = 0, $page = 1)
    {
        $key = md5(snake_case(str_replace('\\', '', __NAMESPACE__).class_basename($this).'_'.__FUNCTION__.'_'.$limit.'_'.$page));
        if ($this->cache->has($key)) {
            $setting = $this->cache->get($key);
        } else {
            $setting = $this->model;
            if (!$limit) {
                $setting_count = $setting->count() > 0 ? $setting->count() : 1;
                $setting = $setting->paginate($setting_count);
            } else {
                $setting = $setting->paginate($limit);
            }
            $this->cache->add($key, $setting, config('cscms.coderstudios.cache_duration'));
        }

        return $setting;
    }
}
