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

namespace CoderStudios\CsCms\Library;

use CoderStudios\CsCms\Models\ArticleType as Model;
use Illuminate\Contracts\Cache\Factory as Cache;

class ArticleType extends BaseLibrary
{
    public function __construct(Model $model, Cache $cache)
    {
        $this->model = $model;
        $this->cache = $cache->store('models');
    }

    public function get($id)
    {
        $key = 'article_type-'.$id;
        if ($this->cache->has($key)) {
            $article = $this->cache->get($key);
        } else {
            $article = $this->model->where('id', $id)->first();
            $this->cache->add($key, $article, config('cscms.coderstudios.cache_duration'));
        }

        return $article;
    }

    public function getAll($limit = 0, $page = 1)
    {
        $key = md5(snake_case(str_replace('\\', '', __NAMESPACE__).class_basename($this).'_'.__FUNCTION__.'_'.$limit.'_'.$page));
        if ($this->cache->has($key)) {
            $article = $this->cache->get($key);
        } else {
            $article = $this->model;
            if (!$limit) {
                $article_count = $article->count() > 0 ? $article->count() : 1;
                $article = $article->paginate($article_count);
            } else {
                $article = $article->paginate($limit);
            }
            $this->cache->add($key, $article, config('cscms.coderstudios.cache_duration'));
        }

        return $article;
    }

    public function getEnabled($enabled = 1, $limit = 0)
    {
        $key = md5(snake_case(str_replace('\\', '', __NAMESPACE__).class_basename($this).'_'.__FUNCTION__.'_'.$limit.'_'.$enabled));
        if ($this->cache->has($key)) {
            $article = $this->cache->get($key);
        } else {
            $article = $this->model->enabled($enabled);
            if (!$limit) {
                $article_count = $article->count() > 0 ? $article->count() : 1;
                $article = $article->paginate($article_count);
            } else {
                $article = $article->paginate($limit);
            }
            $this->cache->add($key, $article, config('cscms.coderstudios.cache_duration'));
        }

        return $article;
    }
}
