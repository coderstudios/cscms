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

namespace CoderStudios\CSCMS\Policies;

use CoderStudios\CSCMS\Models\Capability;
use CoderStudios\CSCMS\Models\User;

class UsersPolicy
{
	public function __construct(Capability $capability)
	{
		$this->capability = $capability;
	}

	public function view(User $user)
	{
		return in_array($this->capability->where('name','view_users')->pluck('id')->first(),$user->role->capabilities()->get()->pluck('id')->toArray());
	}

	public function edit(User $user)
	{
		return in_array($this->capability->where('name','edit_users')->pluck('id')->first(),$user->role->capabilities()->get()->pluck('id')->toArray());
	}

	public function create(User $user)
	{
		return in_array($this->capability->where('name','create_users')->pluck('id')->first(),$user->role->capabilities()->get()->pluck('id')->toArray());
	}

	public function update(User $user)
	{
		return in_array($this->capability->where('name','update_users')->pluck('id')->first(),$user->role->capabilities()->get()->pluck('id')->toArray());
	}

	public function delete(User $user)
	{
		return in_array($this->capability->where('name','delete_users')->pluck('id')->first(),$user->role->capabilities()->get()->pluck('id')->toArray());
	}
}