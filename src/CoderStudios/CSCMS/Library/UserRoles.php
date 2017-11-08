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

use CoderStudios\CSCMS\Models\UserRole as Model;
use Illuminate\Contracts\Cache\Factory as Cache;

class UserRoles extends BaseLibrary {

	public function __construct(Model $model, Cache $cache)
	{
		$this->model = $model;
        $this->cache = $cache->store('models');
	}

	public function get($id)
	{
		$key = 'user_role-' . $id;
		if ($this->cache->has($key)) {
			$user_role = $this->cache->get($key);
		} else {
			$user_role = $this->model->where('id',$id)->first();
			$this->cache->add($key, $user_role, config('cscms.coderstudios.cache_duration'));
		}
		return $user_role;
	}

	public function getAll($limit = 0, $page = 1)
	{
		$key = md5(snake_case(str_replace('\\','',__namespace__) . class_basename($this) . '_' .  __function__ . '_' . $limit . '_' . $page));
		if ($this->cache->has($key)) {
			$user_role = $this->cache->get($key);
		} else {
			$user_role = $this->model;
			if (!$limit) {
				$user_role = $user_role->paginate($user_role->count());
			} else {
				$user_role = $user_role->paginate($limit);
			}
			$this->cache->add($key, $user_role, config('cscms.coderstudios.cache_duration'));
		}
		return $user_role;
	}

	public function getEnabled($enabled = 1, $limit = 0)
	{
		$key = md5(snake_case(str_replace('\\','',__namespace__) . class_basename($this) . '_' .  __function__ . '_' . $limit . '_' . $enabled));
		if ($this->cache->has($key)) {
			$user_role = $this->cache->get($key);
		} else {
			$user_role = $this->model->enabled($enabled);
			if (!$limit) {
				$user_role = $user_role->get();
			} else {
				$user_role = $user_role->paginate($limit);
			}
			$this->cache->add($key, $user_role, config('cscms.coderstudios.cache_duration'));
		}
		return $user_role;
	}
}