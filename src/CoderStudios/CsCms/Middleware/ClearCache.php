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

namespace CoderStudios\CsCms\Middleware;

use Closure;
use Illuminate\Contracts\Cache\Repository as Cache;

class ClearCache
{
    /**
     * Create a new middleware instance.
     */
    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!config('cscms.coderstudios.cache_enabled')) {
            $this->cache->flush();
        }

        if (Session()->get('clear_cache')) {
            $this->cache->flush();
            Session()->remove('clear_cache');
        }

        return $next($request);
    }
}
