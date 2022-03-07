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
 * @copyright  (c) 2022, Coder Studios Ltd
 * @link       https://www.coderstudios.com
 */
 
namespace CoderStudios\CSCMS\Library;

class BaseLibrary {

	protected $cache;

	protected $model;

	public function newInstance()
	{
		return $this->model->newInstance();
	}

	public function create($data)
	{
        $this->cache->flush();
		return $this->model->create($data);
	}

	public function update($id, $data)
	{
        $this->cache->flush();
		return $this->model->where('id',$id)->update($data);
	}

	public function delete($id)
	{
        $this->cache->flush();
		return $this->model->where('id',$id)->delete();
	}

	public function __call($method, $args)
	{
		return call_user_func_array([$this->model, $method], $args);
	}
}