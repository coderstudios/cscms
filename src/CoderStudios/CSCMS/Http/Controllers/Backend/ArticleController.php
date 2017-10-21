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

namespace CoderStudios\CSCMS\Http\Controllers\Backend;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use CoderStudios\CSCMS\Library\Article;
use CoderStudios\CSCMS\Library\Language;
use CoderStudios\CSCMS\Library\ArticleType;
use CoderStudios\CSCMS\Requests\ArticleRequest;
use Illuminate\Contracts\Cache\Factory as Cache;

class ArticleController extends Controller
{
	public function __construct(Request $request, Cache $cache, Article $article, ArticleType $article_type, Language $language)
    {
        $this->request = $request;
    	$this->article = $article;
    	$this->language = $language;
    	$this->article_type = $article_type;
        $this->cache = $cache->store('backend_views');
        $this->attributes = $this->article->getFillable();
    }

	public function index()
	{
		$page_id = 1;
		if ($this->request->get('page')) {
			$page_id = $this->request->get('page');
		}
		$key = md5(snake_case(str_replace('\\','',__namespace__) . class_basename($this) . '_' .  __function__ . '_' . $page_id));
		if ($this->cache->has($key)) {
			$view = $this->cache->get($key);
		} else {
			$new_articles = [];
			$articles = $this->article->getLatestRevisions($this->request->session()->get('config')['config_items_per_page'],$page_id);
			if(count($articles))
				foreach($articles as $article) {
					$article_type = $this->article_type->get($article->article_type_id);
					$article->type = $article_type;
					$new_articles[] = $article;
				}
			$vars = [
				'articles' => $articles,
			];
			$view = view('cscms::backend.pages.article', compact('vars'))->render();
			$this->cache->add($key, $view, config('app.coderstudios.cache_duration'));
		}
		return $view;
	}

	public function create()
	{
		$key = md5(snake_case(str_replace('\\','',__namespace__) . class_basename($this) . '_' .  __function__));
		if ($this->cache->has($key) && !count($this->request->session()->get('errors'))) {
			$view = $this->cache->get($key);
		} else {
			$vars = [
				'form_type'		=> 'create',
				'action'		=> route('backend.articles.article.store'),
				'article'		=> $this->article->newInstance(),
				'parent_id' 	=> '',
				'article_types' => $this->article_type->getAll(),
				'languages' 	=> $this->language->getAll(),
			];
			$view = view('cscms::backend.pages.article-form', compact('vars'))->render();
			$this->cache->add($key, $view, config('app.coderstudios.cache_duration'));
		}
		return $view;
	}

	public function edit($id = '')
	{
		$key = md5(snake_case(str_replace('\\','',__namespace__) . class_basename($this) . '_' .  __function__ . '_' . $id));
		if ($this->cache->has($key) && !count($this->request->session()->get('errors'))) {
			$view = $this->cache->get($key);
		} else {
			$article = $this->article->get($id);
			$vars = [
				'form_type'		=> 'edit',
				'action'		=> route('backend.articles.article.store', ['id' => $id]),
				'article'		=> $article,
				'parent_id' 	=> '',
				'article_types' => $this->article_type->getAll(),
				'languages' 	=> $this->language->getAll(),
				'revisions'		=> $this->article->getRevisions($id)->sortByDesc('id'),
			];
			$view = view('cscms::backend.pages.article-form', compact('vars'))->render();
			$this->cache->add($key, $view, config('app.coderstudios.cache_duration'));
		}
		return $view;
	}

	public function store(ArticleRequest $request)
	{
		$data = $request->only($this->attributes);
		if (empty($data['publish_at'])) {
			$data['publish_at'] = date('Y-m-d H:i:s');
		}
		if (empty($data['sort_order'])) {
			$data['sort_order'] = 0;
		}
		$data['user_id'] = Auth::user()->id;
		if(!empty($request->input('id'))) {
			$data['parent_id'] = $request->input('id');
			if ($data['enabled']) {
				$this->article->where('id',$data['parent_id'])->update(['enabled' => 0,'updated_at' => date('Y-m-d H:i:s')]);
			}
		}
		$article = $this->article->create($data);
		if (count($request->input('description'))) {
			foreach($request->input('description') as $language_id => $content) {
				$data = [
					'language_id'	=> $language_id,
					'content'		=> $content,
				];
				$article->descriptions()->create($data);
			}

		}
		return redirect()->route('backend.articles')->with('success_message','article created');
	}

}