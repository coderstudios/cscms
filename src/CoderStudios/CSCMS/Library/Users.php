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

use CoderStudios\CSCMS\Models\User as Model;
use Illuminate\Contracts\Cache\Factory as Cache;

class Users extends BaseLibrary {

	public function __construct(Model $model, Cache $cache)
	{
		$this->model = $model;
        $this->cache = $cache->store('models');
	}

	public function get($id)
	{
		$key = 'user-' . $id;
		if ($this->cache->has($key)) {
			$user = $this->cache->get($key);
		} else {
			$user = $this->model->where('id',$id)->first();
			$this->cache->add($key, $user, config('cscms.coderstudios.cache_duration'));
		}
		return $user;
	}

	public function getByUsername($username)
	{
		$key = 'userbyusername-' . $username;
		if ($this->cache->has($key)) {
			$user = $this->cache->get($key);
		} else {
			$user = $this->model->where('username',$username)->first();
			if (!$user && is_numeric($username)) {
				$user = $this->model->where('id',$username)->first();
			}
			$this->cache->add($key, $user, config('cscms.coderstudios.cache_duration'));
		}
		return $user;
	}

	public function getByEmail($email)
	{
		$key = 'userbyemail-' . $email;
		if ($this->cache->has($key)) {
			$user = $this->cache->get($key);
		} else {
			$user = $this->model->where('email',$email)->first();
			$this->cache->add($key, $user, config('cscms.coderstudios.cache_duration'));
		}
		return $user;
	}

	public function getAll($limit = 0, $page = 1)
	{
		$key = md5(snake_case(str_replace('\\','',__namespace__) . class_basename($this) . '_' .  __function__ . '_' . $limit . '_' . $page));
		if ($this->cache->has($key)) {
			$user = $this->cache->get($key);
		} else {
			$user = $this->model;
			if (!$limit) {
				$user_count = $user->count() > 0 ? $user->count() : 1;
				$user = $user->paginate($user_count);
			} else {
				$user = $user->paginate($limit);
			}
			$this->cache->add($key, $user, config('cscms.coderstudios.cache_duration'));
		}
		return $user;
	}

	public function getEnabled($enabled = 1, $limit = 0)
	{
		$key = md5(snake_case(str_replace('\\','',__namespace__) . class_basename($this) . '_' .  __function__ . '_' . $limit . '_' . $enabled));
		if ($this->cache->has($key)) {
			$user = $this->cache->get($key);
		} else {
			$user = $this->model->enabled($enabled);
			if (!$limit) {
				$user_count = $user->count() > 0 ? $user->count() : 1;
				$user = $user->paginate($user_count);
			} else {
				$user = $user->paginate($limit);
			}
			$this->cache->add($key, $user, config('cscms.coderstudios.cache_duration'));
		}
		return $user;
	}

	public function isEnabled($data)
	{
		$user = $this->getByEmail($data->request->get('email'));
		if ($user && $user->enabled) {
			return true;
		}
		return false;
	}

	public function isVerified($data)
	{
		$user = $this->getByEmail($data->request->get('email'));
		if ($user && $user->verified) {
			return true;
		}
		return false;
	}
}