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

namespace CoderStudios\CsCms\Http\Controllers\Frontend;

use CoderStudios\CsCms\Http\Controllers\Controller;
use CoderStudios\CsCms\Library\ArticleLibrary;
use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Http\Request;
use View;

class HomeController extends Controller
{
    public function __construct(Request $request, Cache $cache, ArticleLibrary $article)
    {
        $this->article = $article;
        parent::__construct($cache, $request);
    }

    public function index()
    {
        $key = $this->key();
        if ($this->useCachedContent($key)) {
            $view = $this->cache->get($key);
        } else {
            $theme = config('cscms.coderstudios.theme');
            $view_file = 'cscms::frontend.default.pages.index';
            if (View::exists($theme.'.pages.index')) {
                $view_file = $theme.'.pages.index';
            } else {
                $theme = 'default';
            }
            $vars = [
                'theme' => $theme,
            ];
            $view = view($view_file, compact('vars'))->render();
            $this->cache->add($key, $view, config('cscms.coderstudios.cache_duration'));
        }

        return $view;
    }

    public function home()
    {
        $key = $this->key();
        if ($this->useCachedContent($key)) {
            $view = $this->cache->get($key);
        } else {
            $theme = config('cscms.coderstudios.theme');
            $view_file = 'cscms::frontend.default.pages.home';
            if (View::exists($theme.'.pages.home')) {
                $view_file = $theme.'.pages.home';
            } else {
                $theme = 'default';
            }
            $vars = [
                'theme' => $theme,
            ];
            $view = view($view_file, compact('vars'))->render();
            $this->cache->add($key, $view, config('cscms.coderstudios.cache_duration'));
        }

        return $view;
    }

    public function wildcard($slug)
    {
        $article = $this->article
            ->where('slug', $slug)
            ->where('enabled', 1)
            ->orderBy('id', 'DESC')
            ->first()
        ;
        if (is_null($article)) {
            Abort(404);
        }
        $key = $this->key($slug);
        if ($this->useCachedContent($key)) {
            $view = $this->cache->get($key);
        } else {
            $language_id = 1;
            $theme = config('cscms.coderstudios.theme');
            $view_file = 'cscms::frontend.default.pages.page';
            if (View::exists($theme.'.pages.page')) {
                $view_file = $theme.'.pages.page';
            } else {
                $theme = 'default';
            }
            $vars = [
                'theme' => $theme,
                'article' => $article,
                'description' => $article->descriptions()->where('language_id', $language_id)->first(),
            ];
            $view = view($view_file, compact('vars'))->render();
            $this->cache->add($key, $view, config('cscms.coderstudios.cache_duration'));
        }

        return $view;
    }
}
