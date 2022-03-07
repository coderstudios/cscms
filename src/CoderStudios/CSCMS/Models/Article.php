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
use CoderStudios\CSCMS\Traits\ScopeEnabled;
use CoderStudios\CSCMS\Traits\SetEnabledAttribute;

class Article extends Model
{
    use SetEnabledAttribute, ScopeEnabled;

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
    protected $table = 'cscms_articles';

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
    protected $dates = ['publish_at'];

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
        return $this->hasOne('CoderStudios\CSCMS\Models\User','id','user_id');
    }

    public function type()
    {
        return $this->hasOne('CoderStudios\CSCMS\Models\ArticleType','id','article_type_id');
    }

    public function descriptions(){
        return $this->hasMany('CoderStudios\CSCMS\Models\ArticleDescription','article_id','id');
    }

}