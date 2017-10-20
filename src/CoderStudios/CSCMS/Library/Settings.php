<?php
/**
 * Part of the CSCMS package by Coder Studios.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the terms of the MIT license https://opensource.org/licenses/MIT
 *
 * @package    CSCMS
 * @version    1.0.0
 * @author     Coder Studios Ltd
 * @license    MIT https://opensource.org/licenses/MIT
 * @copyright  (c) 2017, Coder Studios Ltd
 * @link       https://www.coderstudios.com
 */
 
namespace CoderStudios\CSCMS\Library;

use CoderStudios\CSCMS\Models\Setting as Model;
use Illuminate\Contracts\Cache\Factory as Cache;

class Settings extends BaseLibrary {

	public function __construct(Model $model, Cache $cache)
	{
		$this->model = $model;
        $this->cache = $cache->store('models');
	}

	public function getSettings()
	{
        $config = $this->getAll();
        $a = [];
        foreach($config as $item) {
            $a[$item->name] = $item->serialized === 1 ? unserialize($item->value) : $item->value;
        }
		return $a;
	}

	public function get($id)
	{
		$key = 'setting-' . $id;
		if ($this->cache->has($key)) {
			$setting = $this->cache->get($key);
		} else {
			$setting = $this->model->where('id',$id)->first();
			$this->cache->add($key, $setting, config('app.coderstudios.cache_duration'));
		}
		return $setting;
	}

    public function getAll($limit = 0, $page = 1)
	{
		$key = md5(snake_case(str_replace('\\','',__namespace__) . class_basename($this) . '_' .  __function__ . '_' . $limit . '_' . $page));
		if ($this->cache->has($key)) {
			$setting = $this->cache->get($key);
		} else {
			$setting = $this->model;
			if (!$limit) {
				$setting = $setting->get();
			} else {
				$setting = $setting->paginate($limit);
			}
			$this->cache->add($key, $setting, config('app.coderstudios.cache_duration'));
		}
		return $setting;
	}
}