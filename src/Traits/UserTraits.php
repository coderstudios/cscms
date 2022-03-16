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

namespace CoderStudios\CsCms\Traits;

trait UserTraits
{
    public function role()
    {
        return $this->hasOne('CoderStudios\CsCms\Models\UserRole', 'id', 'user_role_id');
    }

    public function logs()
    {
        return $this->hasMany('CoderStudios\CsCms\Models\Audits', 'id', 'user_id');
    }
}
