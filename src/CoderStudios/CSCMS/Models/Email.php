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

namespace CoderStudios\CSCMS\Models;

use Illuminate\Database\Eloquent\Model;
use CoderStudios\CSCMS\Traits\SetEnabledAttribute;

class Email extends Model
{
    use SetEnabledAttribute;

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
    protected $table = 'cscms_emails';

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
        'created_at',
        'updated_at',
        'email',
    ];

    public function groups()
    {
        return $this->belongsToMany('CoderStudios\CSCMS\Models\EmailGroup','cscms_emails_email_groups','email_group_id','email_id');
    }
}