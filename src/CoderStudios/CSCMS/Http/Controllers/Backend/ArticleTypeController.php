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

namespace CoderStudios\CSCMS\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Artisan;
use Auth;
use CoderStudios\CSCMS\Library\ArticleType;
use CoderStudios\CSCMS\Requests\ArticleTypeRequest;
use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Http\Request;

class ArticleTypeController extends Controller
{
    public function __construct(Request $request, Cache $cache, ArticleType $article_type)
    {
        $this->request = $request;
        $this->article_type = $article_type;
        $this->cache = $cache->store('backend_views');
        $this->attributes = $this->article_type->getFillable();
    }

    public function index()
    {
        $page_id = 1;
        if ($this->request->get('page')) {
            $page_id = $this->request->get('page');
        }
        $key = md5(snake_case(str_replace('\\', '', __NAMESPACE__).class_basename($this).'_'.__FUNCTION__.'_'.$page_id));
        if ($this->cache->has($key)) {
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
        $key = md5(snake_case(str_replace('\\', '', __NAMESPACE__).class_basename($this).'_'.__FUNCTION__));
        if ($this->cache->has($key) && !count($this->request->session()->get('errors'))) {
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
        $key = md5(snake_case(str_replace('\\', '', __NAMESPACE__).class_basename($this).'_'.__FUNCTION__.'_'.$id));
        if ($this->cache->has($key) && !count($this->request->session()->get('errors'))) {
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
        Artisan::call('cache:clear');

        return redirect()->route('backend.article_types')->with('success_message', 'Article type created');
    }

    public function update(ArticleTypeRequest $request, $id = '')
    {
        $data = $request->only($this->attributes);
        $data['user_id'] = Auth::user()->id;
        $this->article_type->update($id, $data);
        Artisan::call('cache:clear');

        return redirect()->route('backend.article_types')->with('success_message', 'Article type updated');
    }
}
