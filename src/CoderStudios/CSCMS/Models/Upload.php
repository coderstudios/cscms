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
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    use SetEnabledAttribute;
    use ScopeEnabled;

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
    protected $table = 'cscms_uploads';

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
        'sort_order',
        'user_id',
        'filesize',
        'created_at',
        'updated_at',
        'mime',
        'name',
        'filename',
        'generated_filename',
    ];

    public function user()
    {
        return $this->belongsTo('CoderStudios\CsCms\Models\User', 'user_id', 'id');
    }
}
