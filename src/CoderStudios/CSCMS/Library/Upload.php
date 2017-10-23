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

use CoderStudios\CSCMS\Models\Upload as Model;
use Illuminate\Contracts\Cache\Factory as Cache;

class Upload extends BaseLibrary {

	public function __construct(Model $model, Cache $cache)
	{
		$this->model = $model;
        $this->cache = $cache->store('models');
	}

	public function get($id)
	{
		$key = 'upload-' . $id;
		if ($this->cache->has($key)) {
			$upload = $this->cache->get($key);
		} else {
			$upload = $this->model->where('id',$id)->first();
			$this->cache->add($key, $upload, config('cscms.coderstudios.cache_duration'));
		}
		return $upload;
	}

	public function getAll($limit = 0, $page = 1)
	{
		$key = md5(snake_case(str_replace('\\','',__namespace__) . class_basename($this) . '_' .  __function__ . '_' . $limit . '_' . $page));
		if ($this->cache->has($key)) {
			$upoad = $this->cache->get($key);
		} else {
			$upoad = $this->model;
			if (!$limit) {
				$upoad = $upoad->get();
			} else {
				$upoad = $upoad->paginate($limit);
			}
			$this->cache->add($key, $upoad, config('cscms.coderstudios.cache_duration'));
		}
		return $upoad;
	}
}