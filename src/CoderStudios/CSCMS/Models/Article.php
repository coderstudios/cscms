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

class Article extends Model
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
    protected $table = 'cscms_articles';

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
    protected $dates = ['publish_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'enabled',
        'parent_id',
        'user_id',
        'sort_order',
        'article_type_id',
        'created_at',
        'updated_at',
        'publish_at',
        'slug',
        'title',
        'meta_description',
    ];

    public function user()
    {
        return $this->hasOne('CoderStudios\CsCms\Models\User', 'id', 'user_id');
    }

    public function type()
    {
        return $this->hasOne('CoderStudios\CsCms\Models\ArticleType', 'id', 'article_type_id');
    }

    public function descriptions()
    {
        return $this->hasMany('CoderStudios\CsCms\Models\ArticleDescription', 'article_id', 'id');
    }
}
