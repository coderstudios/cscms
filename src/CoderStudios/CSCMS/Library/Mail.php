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

use CoderStudios\CSCMS\Models\Mail as Model;
use Illuminate\Contracts\Cache\Factory as Cache;

class Mail extends BaseLibrary {

	public function __construct(Model $model, Cache $cache)
	{
		$this->model = $model;
        $this->cache = $cache->store('models');
	}

	public function get($id)
	{
		$key = 'email-' . $id;
		if ($this->cache->has($key)) {
			$email = $this->cache->get($key);
		} else {
			$email = $this->model->where('id',$id)->first();
			$this->cache->add($key, $email, config('app.coderstudios.cache_duration'));
		}
		return $email;
	}
}