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

use CoderStudios\CSCMS\Models\Capability as Model;
use Illuminate\Contracts\Cache\Factory as Cache;

class Capability extends BaseLibrary {

	public function __construct(Model $model, Cache $cache)
	{
		$this->model = $model;
        $this->cache = $cache->store('models');
	}

	public function get($id)
	{
		$key = 'capability-' . $id;
		if ($this->cache->has($key)) {
			$capability = $this->cache->get($key);
		} else {
			$capability = $this->capability->where('id',$id)->first();
			$this->cache->add($key, $capability, config('cscms.coderstudios.cache_duration'));
		}
		return $capability;
	}

	public function getAll($limit = 0, $page = 1, $order_by = 'sort_order', $direction = 'asc')
	{
		$key = md5(snake_case(str_replace('\\','',__namespace__) . class_basename($this) . '_' .  __function__ . '_' . $limit . '_' . $page));
		if ($this->cache->has($key)) {
			$capability = $this->cache->get($key);
		} else {
			$capability = $this->model;
			if (!$limit) {
				$capability_count = $capability->count() > 0 ? $capability->count() : 1;
				$capability = $capability->orderBy($order_by,$direction)->paginate($capability_count);
			} else {
				$capability = $capability->orderBy($order_by,$direction)->paginate($limit);
			}
			$this->cache->add($key, $capability, config('cscms.coderstudios.cache_duration'));
		}
		return $capability;
	}

	public function getEnabled($enabled = 1, $limit = 0, $order_by = 'sort_order', $direction = 'asc')
	{
		$key = md5(snake_case(str_replace('\\','',__namespace__) . class_basename($this) . '_' .  __function__ . '_' . $limit . '_' . $enabled));
		if ($this->cache->has($key)) {
			$capability = $this->cache->get($key);
		} else {
			$capability = $this->capability->enabled($enabled);
			if (!$limit) {
				$capability_count = $capability->count() > 0 ? $capability->count() : 1;
				$capability = $capability->orderBy($order_by,$direction)->paginate($capability_count);
			} else {
				$capability = $capability->orderBy($order_by,$direction)->paginate($limit);
			}
			$this->cache->add($key, $capability, config('cscms.coderstudios.cache_duration'));
		}
		return $capability;
	}

}