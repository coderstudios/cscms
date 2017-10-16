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

namespace CoderStudios\CSCMS\Models;

use CoderStudios\Traits\ScopeEnabled;
use Illuminate\Notifications\Notifiable;
use CoderStudios\Traits\SetEnabledAttribute;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, ScopeEnabled, SetEnabledAttribute;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'enabled', 'verified', 'user_role_id', 'created_at', 'updated_at', 'username', 'name', 'email', 'password', 'verified_token', 'remember_token'
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
        return $this->hasOne('CoderStudios\Models\UserRole','id','user_role_id');
    }

    public function logs()
    {
        return $this->hasMany('CoderStudios\Models\Audits','id','user_id');
    }

}