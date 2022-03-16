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

namespace CoderStudios\CsCms\Http\Controllers\Backend;

use Auth;
use CoderStudios\CsCms\Http\Controllers\Controller;
use CoderStudios\CsCms\Library\ArticleLibrary;
use CoderStudios\CsCms\Library\ArticleTypeLibrary;
use CoderStudios\CsCms\Library\LanguageLibrary;
use CoderStudios\CsCms\Requests\ArticleRequest;
use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function __construct(Request $request, Cache $cache, ArticleLibrary $article, ArticleTypeLibrary $article_type, LanguageLibrary $language)
    {
        $this->article = $article;
        $this->language = $language;
        $this->article_type = $article_type;
        $this->attributes = $this->article->getFillable();
        parent::__construct($cache, $request);
    }

    public function index()
    {
        $page_id = $this->getPage();
        $key = $this->key();
        if ($this->useCachedContent($key)) {
            $view = $this->cache->get($key);
        } else {
            $new_articles = [];

            $total_articles_count = (int) $this->article->getLatestRevisionsCount();
            $articles = $this->article->getLatestRevisions($this->request->config['config_items_per_page'], $page_id);
            if (count($articles)) {
                foreach ($articles as $article) {
                    $article_type = $this->article_type->get($article->article_type_id);
                    $article->type = $article_type;
                    $new_articles[] = $article;
                }
            }
            $vars = [
                'articles' => $articles,
                'total_articles_count' => $total_articles_count,
            ];
            $view = view('cscms::backend.pages.article', compact('vars'))->render();
            $this->cache->add($key, $view, config('cscms.coderstudios.cache_duration'));
        }

        return $view;
    }

    public function create()
    {
        $key = $this->key();
        if ($this->useCachedContent($key)) {
            $view = $this->cache->get($key);
        } else {
            $vars = [
                'form_type' => 'create',
                'action' => route('backend.articles.article.store'),
                'article' => $this->article->newInstance(),
                'parent_id' => '',
                'article_types' => $this->article_type->get(),
                'languages' => $this->language->get(),
            ];
            $view = view('cscms::backend.pages.article-form', compact('vars'))->render();
            $this->cache->add($key, $view, config('cscms.coderstudios.cache_duration'));
        }

        return $view;
    }

    public function edit($id = '')
    {
        $key = $this->key();
        if ($this->useCachedContent($key)) {
            $view = $this->cache->get($key);
        } else {
            $article = $this->article->get($id);
            $vars = [
                'form_type' => 'edit',
                'action' => route('backend.articles.article.store', ['id' => $id]),
                'article' => $article,
                'parent_id' => '',
                'article_types' => $this->article_type->get(),
                'languages' => $this->language->get(),
                'revisions' => $this->article->getRevisions($id)->sortByDesc('id'),
            ];
            $view = view('cscms::backend.pages.article-form', compact('vars'))->render();
            $this->cache->add($key, $view, config('cscms.coderstudios.cache_duration'));
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
        if (!empty($request->input('id'))) {
            $data['parent_id'] = $request->input('id');
            if ($data['enabled']) {
                $this->article->where('id', $data['parent_id'])->update(['enabled' => 0, 'updated_at' => date('Y-m-d H:i:s')]);
            }
        }
        $article = $this->article->create($data);
        if (count($request->input('description'))) {
            foreach ($request->input('description') as $language_id => $content) {
                $data = [
                    'language_id' => $language_id,
                    'content' => $content,
                ];
                $article->descriptions()->create($data);
            }
        }
        $this->cache->flush();

        return redirect()->route('backend.articles')->with('success', 'article created');
    }
}
