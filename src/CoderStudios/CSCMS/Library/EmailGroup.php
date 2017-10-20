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

use CoderStudios\CSCMS\Models\EmailGroup as Model;
use Illuminate\Contracts\Cache\Factory as Cache;

class EmailGroup extends BaseLibrary {

	public function __construct(Model $model, Cache $cache)
	{
		$this->model = $model;
        $this->cache = $cache->store('models');
	}

	public function get($id)
	{
		$key = 'email_group-' . $id;
		if ($this->cache->has($key)) {
			$email_group = $this->cache->get($key);
		} else {
			$email_group = $this->model->where('id',$id)->first();
			$this->cache->add($key, $email_group, config('app.coderstudios.cache_duration'));
		}
		return $email_group;
	}

	public function getAll($limit = 0, $page = 1)
	{
		$key = md5(snake_case(str_replace('\\','',__namespace__) . class_basename($this) . '_' .  __function__ . '_' . $limit . '_' . $page));
		if ($this->cache->has($key)) {
			$email_group = $this->cache->get($key);
		} else {
			$email_group = $this->model;
			if (!$limit) {
				$email_group = $email_group->get();
			} else {
				$email_group = $email_group->paginate($limit);
			}
			$this->cache->add($key, $email_group, config('app.coderstudios.cache_duration'));
		}
		return $email_group;
	}

	public function getEnabled($enabled = 1, $limit = 0)
	{
		$key = md5(snake_case(str_replace('\\','',__namespace__) . class_basename($this) . '_' .  __function__ . '_' . $limit . '_' . $enabled));
		if ($this->cache->has($key)) {
			$email_group = $this->cache->get($key);
		} else {
			$email_group = $this->model->enabled($enabled);
			if (!$limit) {
				$email_group = $email_group->get();
			} else {
				$email_group = $email_group->paginate($limit);
			}
			$this->cache->add($key, $email_group, config('app.coderstudios.cache_duration'));
		}
		return $email_group;
	}
}