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
 
namespace CoderStudios\CSCMS\Library;

use DB;
use CoderStudios\CSCMS\Models\Article as Model;
use Illuminate\Contracts\Cache\Factory as Cache;

class Article extends BaseLibrary  {

	public function __construct(Model $model, Cache $cache)
	{
		$this->model = $model;
        $this->cache = $cache->store('models');
	}

	public function get($id)
	{
		$key = 'article-' . $id;
		if ($this->cache->has($key)) {
			$article = $this->cache->get($key);
		} else {
			$article = $this->model->where('id',$id)->first();
			$this->cache->add($key, $article, config('app.coderstudios.cache_duration'));
		}
		return $article;
	}

	public function getByParentId($id)
	{
		$key = 'article_parent-' . $id;
		if ($this->cache->has($key)) {
			$article = $this->cache->get($key);
		} else {
			$article = $this->model->where('parent_id',$id)->first();
			$this->cache->add($key, $article, config('app.coderstudios.cache_duration'));
		}
		return $article;
	}

	public function getAll($limit = 0, $page = 1)
	{
		$key = md5(snake_case(str_replace('\\','',__namespace__) . class_basename($this) . '_' .  __function__ . '_' . $limit . '_' . $page));
		if ($this->cache->has($key)) {
			$article = $this->cache->get($key);
		} else {
			$article = $this->model;
			if (!$limit) {
				$article = $article->get();
			} else {
				$article = $article->paginate($limit);
			}
			$this->cache->add($key, $article, config('app.coderstudios.cache_duration'));
		}
		return $article;
	}

	private function getArticleIdsToParent($id,$ids = [])
	{
		$ids[] = $id;
		$article = $this->get($id);
		if ($article->parent_id) {
			$article_ids = $this->getArticleIdsToParent($article->parent_id,$ids);
			if (!is_object($article_ids)) {
				return $article_ids;
			}
		}
		return $ids;
	}

	private function getArticleIdsToChild($id,$ids = [])
	{
		$ids[] = $id;
		$article = $this->get($id);
		if ($article->parent_id && !in_array($article->id,$ids)) {
			$article_ids = $this->getArticleIdsToChild($article->id,$ids);
			if (!is_object($article_ids)) {
				return $article_ids;
			}
		} else {
			$article = $this->getByParentId($id);
			if (is_object($article) && $article->parent_id) {
				$article_ids = $this->getArticleIdsToChild($article->id,$ids);
				if (!is_object($article_ids)) {
					return $article_ids;
				}
			}
			$ids[] = $article;
		}
		return $ids;
	}

	public function getRevisions($post_id)
	{
		$key = md5(snake_case(str_replace('\\','',__namespace__) . class_basename($this) . '_' .  __function__ . '_' . $post_id));
		if ($this->cache->has($key)) {
			$article = $this->cache->get($key);
		} else {
			$children = $this->getArticleIdsToChild($post_id);
			$parents = $this->getArticleIdsToParent($post_id);
			$ids = array_filter(array_unique(array_merge($children,$parents)));
			$article = $this->model->whereIn('id',$ids)->get();
			$this->cache->add($key, $article, config('app.coderstudios.cache_duration'));
		}
		return $article;
	}

	public function getLatestRevisions($limit = 0, $page = 1)
	{
		$key = md5(snake_case(str_replace('\\','',__namespace__) . class_basename($this) . '_' .  __function__ . '_' . $limit . '_' . $page));
		if ($this->cache->has($key)) {
			$article = $this->cache->get($key);
		} else {
			$article = DB::table('cscms_articles as a')
				->leftJoin('cscms_articles as b','a.id','=',DB::raw('(select b.parent_id from cscms_articles b where b.parent_id = a.id order by b.id desc)'))
				->select(DB::raw('a.*, max(b.id) as latest_revision_id'))
				->whereNull('a.parent_id')
				->groupBy('a.id','a.enabled','a.parent_id','a.user_id','a.sort_order','a.article_type_id','a.created_at','a.updated_at','a.publish_at','a.slug','a.title','a.meta_description');
			if (!$limit) {
				$article = $article->get();
			} else {
				$article = $article->paginate($limit);
			}
			$this->cache->add($key, $article, config('app.coderstudios.cache_duration'));
		}
		return $article;
	}

	public function getEnabled($enabled = 1, $limit = 0)
	{
		$key = md5(snake_case(str_replace('\\','',__namespace__) . class_basename($this) . '_' .  __function__ . '_' . $limit . '_' . $enabled));
		if ($this->cache->has($key)) {
			$article = $this->cache->get($key);
		} else {
			$article = $this->model->enabled($enabled);
			if (!$limit) {
				$article = $article->get();
			} else {
				$article = $article->paginate($limit);
			}
			$this->cache->add($key, $article, config('app.coderstudios.cache_duration'));
		}
		return $article;
	}
}