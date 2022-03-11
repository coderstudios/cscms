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

namespace CoderStudios\CsCms\Library;

use CoderStudios\CsCms\Models\Article as Model;
use DB;
use Illuminate\Contracts\Cache\Factory as Cache;

class ArticleLibrary extends BaseLibrary
{
    public function __construct(Model $model, Cache $cache)
    {
        $this->model = $model;
        $this->cache = $cache->store(config('cache.default'));
    }

    public function getByParentId($id)
    {
        $key = $this->key('article_parent-'.$id);
        if ($this->useCachedContent($key)) {
            $article = $this->cache->get($key);
        } else {
            $article = $this->model->where('parent_id', $id)->first();
            $this->cache->add($key, $article, config('cscms.coderstudios.cache_duration'));
        }

        return $article;
    }

    public function getRevisions($post_id)
    {
        $key = $this->key($post_id);
        if ($this->useCachedContent($key)) {
            $article = $this->cache->get($key);
        } else {
            $children = $this->getArticleIdsToChild($post_id);
            $parents = $this->getArticleIdsToParent($post_id);
            $ids = array_filter(array_unique(array_merge($children, $parents)));
            $article = $this->model->whereIn('id', $ids)->get();
            $this->cache->add($key, $article, config('cscms.coderstudios.cache_duration'));
        }

        return $article;
    }

    public function getLatestRevisions($limit = 0, $page = 1)
    {
        $key = $this->key($limit.'_'.$page);
        if ($this->useCachedContent($key)) {
            $article = $this->cache->get($key);
        } else {
            $article = DB::select(DB::raw('SELECT * FROM (SELECT max(id) as id,slug From cscms_articles group by slug) As idx Inner Join cscms_articles ON idx.id=cscms_articles.id'));
            $ids = [];
            if (count($article)) {
                foreach ($article as $a) {
                    $ids[] = $a->id;
                }
            } else {
                $article = null;
            }
            if (count($ids)) {
                $article = $this->model->whereIn('id', $ids)->orderBy('id', 'DESC');
                if (!$limit) {
                    $article_count = $article->count() > 0 ? $article->count() : 1;
                    $article = $article->paginate($article_count);
                } else {
                    $article = $article->paginate($limit);
                }
            }
            $this->cache->add($key, $article, config('cscms.coderstudios.cache_duration'));
        }

        return $article;
    }

    public function getLatestRevisionsCount()
    {
        $key = $this->key();
        if ($this->useCachedContent($key)) {
            $article = $this->cache->get($key);
        } else {
            $article = DB::select(DB::raw('SELECT * FROM (SELECT max(id) as id,slug From cscms_articles group by slug) As idx Inner Join cscms_articles ON idx.id=cscms_articles.id'));
            $ids = [];
            if (count($article)) {
                foreach ($article as $a) {
                    $ids[] = $a->id;
                }
            } else {
                $article = null;
            }
            $article_count = 1;
            if (count($ids)) {
                $article = $this->model->whereIn('id', $ids)->orderBy('id', 'DESC');
                $article_count = $article->count() > 0 ? $article->count() : 1;
            }
            $this->cache->add($key, $article_count, config('cscms.coderstudios.cache_duration'));
        }

        return $article_count;
    }

    private function getArticleIdsToParent($id, $ids = [])
    {
        $ids[] = $id;
        $article = $this->get($id);
        if ($article->parent_id) {
            $article_ids = $this->getArticleIdsToParent($article->parent_id, $ids);
            if (!is_object($article_ids)) {
                return $article_ids;
            }
        }

        return $ids;
    }

    private function getArticleIdsToChild($id, $ids = [])
    {
        $ids[] = $id;
        $article = $this->get($id);
        if ($article->parent_id && !in_array($article->id, $ids)) {
            $article_ids = $this->getArticleIdsToChild($article->id, $ids);
            if (!is_object($article_ids)) {
                return $article_ids;
            }
        } else {
            $article = $this->getByParentId($id);
            if (is_object($article) && $article->parent_id) {
                $article_ids = $this->getArticleIdsToChild($article->id, $ids);
                if (!is_object($article_ids)) {
                    return $article_ids;
                }
            }
            $ids[] = $article;
        }

        return $ids;
    }
}
