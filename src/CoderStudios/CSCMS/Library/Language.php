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

use CoderStudios\CSCMS\Models\Language as Model;
use Illuminate\Contracts\Cache\Factory as Cache;

class Language extends BaseLibrary {

	public function __construct(Model $model, Cache $cache)
	{
		$this->model = $model;
        $this->cache = $cache->store('models');
	}

	public function get($id)
	{
		$key = 'language-' . $id;
		if ($this->cache->has($key)) {
			$language = $this->cache->get($key);
		} else {
			$language = $this->model->where('id',$id)->first();
			$this->cache->add($key, $language, config('cscms.coderstudios.cache_duration'));
		}
		return $language;
	}

	public function getAll($limit = 0, $page = 1)
	{
		$key = md5(snake_case(str_replace('\\','',__namespace__) . class_basename($this) . '_' .  __function__ . '_' . $limit . '_' . $page));
		if ($this->cache->has($key)) {
			$email_group = $this->cache->get($key);
		} else {
			$language = $this->model;
			if (!$limit) {
				$language = $language->paginate($language->count());
			} else {
				$language = $language->paginate($limit);
			}
			$this->cache->add($key, $language, config('cscms.coderstudios.cache_duration'));
		}
		return $language;
	}
}