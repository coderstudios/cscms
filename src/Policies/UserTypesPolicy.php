<?php
/**
 * Part of the CsCms package by Coder Studios.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the terms of the MIT license https://opensource.org/licenses/MIT
 *
 * @version    1.0.0
 *
 * @author     Coder Studios Ltd
 * @license    MIT https://opensource.org/licenses/MIT
 * @copyright  (c) 2022, Coder Studios Ltd
 *
 * @see       https://www.coderstudios.com
 */

namespace CoderStudios\CsCms\Policies;

use CoderStudios\CsCms\Models\Capability;

class UserTypesPolicy
{
    public function __construct(Capability $capability)
    {
        $this->capability = $capability;
    }

    public function view()
    {
        return in_array($this->capability->where('name', 'view_user_roles')->pluck('id')->first(), request()->user()->role->capabilities()->get()->pluck('id')->toArray());
    }

    public function edit()
    {
        return in_array($this->capability->where('name', 'edit_user_roles')->pluck('id')->first(), request()->user()->role->capabilities()->get()->pluck('id')->toArray());
    }

    public function create()
    {
        return in_array($this->capability->where('name', 'create_user_roles')->pluck('id')->first(), request()->user()->role->capabilities()->get()->pluck('id')->toArray());
    }

    public function update()
    {
        return in_array($this->capability->where('name', 'update_user_roles')->pluck('id')->first(), request()->user()->role->capabilities()->get()->pluck('id')->toArray());
    }

    public function delete()
    {
        return in_array($this->capability->where('name', 'delete_user_roles')->pluck('id')->first(), request()->user()->role->capabilities()->get()->pluck('id')->toArray());
    }
}
