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
 
namespace CoderStudios\CSCMS\Traits;

trait SetEnabledAttribute {

	/**
	 * Set enabled attribute
	 * @param  $value
	 * @return collection
	 */
	public function setEnabledAttribute($value)
	{
		if (empty($value)) {
			$this->attributes['enabled'] = 0;
		} else {
			$this->attributes['enabled'] = $value;
		}
	}

}