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
use CoderStudios\CsCms\Library\ArticleTypeLibrary;
use CoderStudios\CsCms\Requests\ArticleTypeRequest;
use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Http\Request;

class ArticleTypeController extends Controller
{
    public function __construct(Request $request, Cache $cache, ArticleTypeLibrary $article_type)
    {
        $this->article_type = $article_type;
        $this->attributes = $this->article_type->getFillable();
        parent::__construct($cache, $request);
    }

    public function index()
    {
        $page_id = $this->getPage();
        $key = $this->key();
        if ($this->useCachedContent($key)) {
            $view = $this->cache->get($key);
        } else {
            $vars = [
                'article_types' => $this->article_type->getAll($this->request->config['config_items_per_page'], $page_id),
            ];
            $view = view('cscms::backend.pages.article_type', compact('vars'))->render();
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
                'id' => '',
                'form_type' => 'create',
                'action' => route('backend.article_types.article_type.store'),
                'article_type' => $this->article_type->newInstance(),
            ];
            $view = view('cscms::backend.pages.article_type-form', compact('vars'))->render();
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
            $article_type = $this->article_type->get($id);
            $vars = [
                'id' => $id,
                'form_type' => 'edit',
                'action' => route('backend.article_types.article_type.update', ['id' => $id]),
                'article_type' => $article_type,
            ];
            $view = view('cscms::backend.pages.article_type-form', compact('vars'))->render();
            $this->cache->add($key, $view, config('cscms.coderstudios.cache_duration'));
        }

        return $view;
    }

    public function store(ArticleTypeRequest $request, $id = '')
    {
        $data = $request->only($this->attributes);
        $data['user_id'] = Auth::user()->id;
        $this->article_type->create($data);
        $this->cache->flush();

        return redirect()->route('backend.article_types')->with('success', 'Article type created');
    }

    public function update(ArticleTypeRequest $request, $id = '')
    {
        $data = $request->only($this->attributes);
        $data['user_id'] = Auth::user()->id;
        $this->article_type->update($id, $data);
        $this->cache->flush();

        return redirect()->route('backend.article_types')->with('success', 'Article type updated');
    }
}
