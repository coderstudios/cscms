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

namespace CoderStudios\CSCMS\Http\Controllers\Frontend;

use View;
use App\Http\Controllers\Controller;
use CoderStudios\CSCMS\Library\Article;
use Illuminate\Contracts\Cache\Factory as Cache;

class HomeController extends Controller
{
    public function __construct(Cache $cache, Article $article)
    {
        $this->article = $article;
        $this->cache = $cache->store('frontend_views');
    }

	public function index()
	{
        $key = md5(snake_case(str_replace('\\','',__namespace__) . class_basename($this) . '_' .  __function__));
		if ($this->cache->has($key)) {
			$view = $this->cache->get($key);
		} else {
            $theme = config('cscms.coderstudios.theme');
            $vars = [ 
                'theme' => $theme,
            ];
            $view_file = 'cscms::frontend.default.pages.index';
            if (View::exists('cscms::frontend.'.$theme.'.pages.index')) {
                $view_file = 'cscms::frontend.'.$theme.'.pages.index';
            }
            $view = view($view_file, compact('vars'))->render();
			$this->cache->add($key, $view, config('cscms.coderstudios.cache_duration'));
        }
        return $view;
	}

    public function home()
    {
        $key = md5(snake_case(str_replace('\\','',__namespace__) . class_basename($this) . '_' .  __function__));
        if ($this->cache->has($key)) {
            $view = $this->cache->get($key);
        } else {
            $theme = config('cscms.coderstudios.theme');
            $vars = [
                'theme' => $theme,
            ];
            $view_file = 'cscms::frontend.default.pages.index';
            if (View::exists('cscms::frontend.'.$theme.'.pages.index')) {
                $view_file = 'cscms::frontend.'.$theme.'.pages.index';
            }
            $view = view($view_file, compact('vars'))->render();
            $this->cache->add($key, $view, config('cscms.coderstudios.cache_duration'));
        }
        return $view;
    }

    public function wildcard($slug)
    {
        $article = $this->article
            ->where('slug',$slug)
            ->where('enabled',1)
            ->first();
        if (is_null($article)) {
            Abort(404);
        }
        $key = md5(snake_case(str_replace('\\','',__namespace__) . class_basename($this) . '_' .  __function__ . '_' . $slug));
        if ($this->cache->has($key)) {
            $view = $this->cache->get($key);
        } else {
            $language_id = 1;
            $theme = config('cscms.coderstudios.theme');
            $vars = [
                'theme' => $theme,
                'article' => $article,
                'description' => $article->descriptions()->where('language_id',$language_id)->first(),
            ];
            $theme = config('cscms.coderstudios.theme');
            $view_file = 'cscms::frontend.default.pages.page';
            if (View::exists('cscms::frontend.'.$theme.'.pages.page')) {
                $view_file = 'cscms::frontend.'.$theme.'.pages.page';
            }
            $view = view($view_file, compact('vars'))->render();
            $this->cache->add($key, $view, config('cscms.coderstudios.cache_duration'));
        }
        return $view;
    }
}