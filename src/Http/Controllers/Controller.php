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

namespace CoderStudios\CsCms\Http\Controllers;

use App\Http\Controllers\Controller as BaseController;
use CoderStudios\CsCms\Traits\AccessDenied;
use CoderStudios\CsCms\Traits\CachedContent;
use CoderStudios\CsCms\Traits\Filled;
use CoderStudios\CsCms\Traits\GenerateKey;
use CoderStudios\CsCms\Traits\GetPage;
use CoderStudios\CsCms\Traits\NotFound;
use CoderStudios\CsCms\Traits\Shrink;
use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Http\Request;

abstract class Controller extends BaseController
{
    use GenerateKey;
    use CachedContent;
    use Shrink;
    use AccessDenied;
    use NotFound;
    use Filled;
    use GetPage;

    protected $cache;
    protected $request;

    /**
     * Create a new controller instance.
     */
    public function __construct(Cache $cache, Request $request)
    {
        $this->request = $request;
        $this->cache = $cache->store(config('cache.default'));
    }

    public function getDir($str = 'DESC')
    {
        return 'DESC' == $str ? 'ASC' : 'DESC';
    }
}
