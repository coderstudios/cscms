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
use CoderStudios\CsCms\Library\Users;
use CoderStudios\CsCms\Requests\UpdateMemberRequest;
use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Http\Request;
use View;

class UserController extends Controller
{
    public function __construct(Request $request, Cache $cache, Users $user)
    {
        $this->middleware('auth');
        $this->user = $user;
        parent::__construct($cache, $request);
    }

    public function profile()
    {
        $key = $this->key();
        if ($this->useCachedContent($key)) {
            $view = $this->cache->get($key);
        } else {
            $theme = config('cscms.coderstudios.theme');
            $view_file = 'cscms::frontend.default.pages.profile';
            if (View::exists($theme.'.pages.profile')) {
                $view_file = $theme.'.pages.profile';
            } else {
                $theme = 'default';
            }
            $vars = [
                'action' => route('frontend.profile.update'),
                'user' => $current_user,
                'theme' => $theme,
            ];
            $view = view($view_file, compact('vars'))->render();
            $this->cache->add($key, $view, config('cscms.coderstudios.cache_duration'));
        }

        return $view;
    }

    public function verifyAccount($token)
    {
        if (!empty($token)) {
            $user = $this->user->where('verified_token', $token)->first();
            if ($user) {
                $user->verified = 1;
                $user->save();

                return redirect()->route('frontend.login')->with('success_message', 'Account verified, login to continue');
            }
        }

        return redirect()->route('frontend.index');
    }

    public function updateProfile(UpdateMemberRequest $request)
    {
        $data = $request->only('name', 'email');
        $data['enabled'] = 1;
        $this->user->update($request->input('id'), $data);

        return redirect()->route('frontend.profile')->with('success_message', 'Profile updated');
    }
}
