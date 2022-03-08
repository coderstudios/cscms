<?php
/**
 * Part of the CSCMS package by Coder Studios.
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

use CoderStudios\CsCms\Traits\SetEnabledAttribute;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use SetEnabledAttribute;

    /**
     * Enable eloquent timestamps.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The database connection used with the model.
     *
     * @var string
     */
    protected $connection = 'mysql';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cscms_emails';

    /**
     * The attributes that should be hidden from arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The default attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Carbon converted dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'enabled',
        'created_at',
        'updated_at',
        'email',
    ];

    public function groups()
    {
        return $this->belongsToMany('CoderStudios\CsCms\Models\EmailGroup', 'cscms_emails_email_groups', 'email_group_id', 'email_id');
    }
}
