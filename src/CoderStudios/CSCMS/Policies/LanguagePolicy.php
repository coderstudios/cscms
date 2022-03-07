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

namespace CoderStudios\CSCMS\Policies;

use CoderStudios\CSCMS\Models\Capability;
use CoderStudios\CSCMS\Models\User;

class LanguagePolicy
{
	public function __construct(Capability $capability)
	{
		$this->capability = $capability;
	}

	public function view(User $user)
	{
		return in_array($this->capability->where('name','view_language')->pluck('id')->first(),$user->role->capabilities()->get()->pluck('id')->toArray());
	}

	public function edit(User $user)
	{
		return in_array($this->capability->where('name','edit_language')->pluck('id')->first(),$user->role->capabilities()->get()->pluck('id')->toArray());
	}

	public function create(User $user)
	{
		return in_array($this->capability->where('name','create_language')->pluck('id')->first(),$user->role->capabilities()->get()->pluck('id')->toArray());
	}

	public function update(User $user)
	{
		return in_array($this->capability->where('name','update_language')->pluck('id')->first(),$user->role->capabilities()->get()->pluck('id')->toArray());
	}

	public function delete(User $user)
	{
		return in_array($this->capability->where('name','delete_language')->pluck('id')->first(),$user->role->capabilities()->get()->pluck('id')->toArray());
	}
}