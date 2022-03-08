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

namespace CoderStudios\CsCms\Models;

use CoderStudios\CsCms\Traits\ScopeEnabled;
use CoderStudios\CsCms\Traits\SetEnabledAttribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    use ScopeEnabled;
    use SetEnabledAttribute;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'enabled', 'verified', 'user_role_id', 'created_at', 'updated_at', 'username', 'name', 'email', 'password', 'verified_token', 'remember_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function role()
    {
        return $this->hasOne('CoderStudios\CsCms\Models\UserRole', 'id', 'user_role_id');
    }

    public function logs()
    {
        return $this->hasMany('CoderStudios\CsCms\Models\Audits', 'id', 'user_id');
    }
}
