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

namespace CoderStudios\CsCms\Library;

use CoderStudios\CsCms\Models\User as Model;
use Illuminate\Contracts\Cache\Factory as Cache;

class UsersLibrary extends BaseLibrary
{
    public function __construct(Model $model, Cache $cache)
    {
        $this->model = $model;
        $this->cache = $cache->store(config('cache.default'));
    }

    public function getByUsername($username)
    {
        $key = 'userbyusername-'.$username;
        if ($this->useCachedContent($key)) {
            $user = $this->cache->get($key);
        } else {
            $user = $this->model->where('username', $username)->first();
            if (!$user && is_numeric($username)) {
                $user = $this->model->where('id', $username)->first();
            }
            $this->cache->add($key, $user, config('cscms.coderstudios.cache_duration'));
        }

        return $user;
    }

    public function getByEmail($email)
    {
        $key = 'userbyemail-'.$email;
        if ($this->useCachedContent($key)) {
            $user = $this->cache->get($key);
        } else {
            $user = $this->model->where('email', $email)->first();
            $this->cache->add($key, $user, config('cscms.coderstudios.cache_duration'));
        }

        return $user;
    }

    public function isEnabled($data)
    {
        $user = $this->getByEmail($data->request->get('email'));
        if ($user && $user->enabled) {
            return true;
        }

        return false;
    }

    public function isVerified($data)
    {
        $user = $this->getByEmail($data->request->get('email'));
        if ($user && $user->verified) {
            return true;
        }

        return false;
    }
}
