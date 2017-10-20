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
use Illuminate\Database\Eloquent\Model;
use CoderStudios\Traits\SetEnabledAttribute;

class UserRole extends Model
{
    use ScopeEnabled, SetEnabledAttribute;

    /**
    * The database connection used with the model.
    *
    * @var  string
    */
    protected $connection = 'mysql';

    /**
    * The table associated with the model.
    *
    * @var  string
    */
    protected $table = 'user_roles';

    /**
    * The attributes that should be hidden from arrays.
    *
    * @var  array
    */
    protected $hidden = [];

    /**
    * The default attributes.
    *
    * @var  array
    */
    protected $attributes = [];

    /**
    * Carbon converted dates.
    *
    * @var  array
    */
    protected $dates = [];

    /**
    * Enable eloquent timestamps.
    *
    * @var  boolean
    */
    public $timestamps = true;

    /**
    * The attributes that are mass assignable.
    *
    * @var  array
    */
    protected $fillable = [
        'enabled',
        'sort_order',
        'created_at',
        'updated_at',
        'name',
    ];

    public function user()
    {
        return $this->belongsTo('CoderStudios\CSCMS\Models\User','id','user_role_id');
    }

    public function capabilities()
    {
        return $this->belongsToMany('CoderStudios\CSCMS\Models\Capability','capabilities_user_roles','user_role_id','capability_id')->withTimestamps();
    }

}