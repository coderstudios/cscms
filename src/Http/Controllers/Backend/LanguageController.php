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

use CoderStudios\CsCms\Http\Controllers\Controller;
use Auth;
use CoderStudios\CsCms\Library\Language;
use CoderStudios\CsCms\Requests\LanguageRequest;
use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function __construct(Request $request, Cache $cache, Language $language)
    {
        $this->request = $request;
        $this->language = $language;
        $this->cache = $cache->store(config('cache.default'));
        $this->attributes = $this->language->getFillable();
    }

    public function index()
    {
        $page_id = 1;
        if ($this->request->get('page')) {
            $page_id = $this->request->get('page');
        }
        $key = md5(snake_case(str_replace('\\', '', __NAMESPACE__).class_basename($this).'_'.__FUNCTION__.'_'.$page_id));
                if ($this->useCachedContent($key)) {
            $view = $this->cache->get($key);
        } else {
            $vars = [
                'languages' => $this->language->getAll($this->request->config['config_items_per_page'], $page_id),
            ];
            $view = view('cscms::backend.pages.language', compact('vars'))->render();
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
                'form_type' => 'create',
                'action' => route('backend.languages.language.store'),
                'language' => $this->language->newInstance(),
            ];
            $view = view('cscms::backend.pages.language-form', compact('vars'))->render();
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
            $language = $this->language->get($id);
            $vars = [
                'form_type' => 'edit',
                'action' => route('backend.languages.language.update', ['id' => $id]),
                'language' => $language,
            ];
            $view = view('cscms::backend.pages.language-form', compact('vars'))->render();
            $this->cache->add($key, $view, config('cscms.coderstudios.cache_duration'));
        }

        return $view;
    }

    public function store(LanguageRequest $request, $id = '')
    {
        $data = $request->only($this->attributes);
        $data['user_id'] = Auth::user()->id;
        $notification = $this->language->create($data);

        return redirect()->route('backend.languages')->with('success_message', 'Language created');
    }

    public function update(LanguageRequest $request, $id = '')
    {
        $data = $request->only($this->attributes);
        $data['user_id'] = Auth::user()->id;
        $this->language->update($id, $data);

        return redirect()->route('backend.languages')->with('success_message', 'Language updated');
    }
}
