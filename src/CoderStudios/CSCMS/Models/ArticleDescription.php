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

use Illuminate\Database\Eloquent\Model;

class ArticleDescription extends Model
{

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
    protected $table = 'articles_description';

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
    public $timestamps = false;

    /**
    * The attributes that are mass assignable.
    *
    * @var  array
    */
    protected $fillable = [
        'article_id',
        'language_id',
        'content',
    ];

    public function article()
    {
        return $this->belongsTo('CoderStudios\Models\Article','article_id','id');
    }

    public function language()
    {
        return $this->belongsTo('CoderStudios\Models\Language','language_id','id');
    }

}