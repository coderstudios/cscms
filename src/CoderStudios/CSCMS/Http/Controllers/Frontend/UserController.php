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

use Auth;
use View;
use App\Http\Controllers\Controller;
use CoderStudios\CSCMS\Library\Users;
use Illuminate\Contracts\Cache\Factory as Cache;
use CoderStudios\CSCMS\Requests\UpdateMemberRequest;

class UserController extends Controller
{
    public function __construct(Cache $cache, Users $user)
    {
        $this->cache = $cache->store('frontend_views');
        $this->middleware('auth');
        $this->user = $user;
    }

    public function profile()
    {
        $current_user = Auth::user();
        $key = md5(snake_case(str_replace('\\','',__namespace__) . class_basename($this) . '_' .  __function__ . '_' . $current_user->id));
        if ($this->cache->has($key)) {
            $view = $this->cache->get($key);
        } else {
            $theme = config('cscms.coderstudios.theme');
            $vars = [
                'action' => route('frontend.profile.update'),
                'user' => $current_user,
                'theme' => $theme,
            ];
            $view_file = 'cscms::frontend.default.pages.profile';
            if (View::exists($theme.'.pages.profile')) {
                $view_file = $theme.'.pages.profile';
            }
            $view = view($view_file, compact('vars'))->render();
            $this->cache->add($key, $view, config('cscms.coderstudios.cache_duration'));
        }
        return $view;

    }

    public function verifyAccount($token)
    {
        if (!empty($token)) {
            $user = $this->user->where('verified_token',$token)->first();
            if ($user) {
                $user->verified = 1;
                $user->save();
                return redirect()->route('frontend.login')->with('success_message','Account verified, login to continue');
            }
        }
        return redirect()->route('frontend.index');
    }

    public function updateProfile(UpdateMemberRequest $request)
    {
        $data = $request->only('name','email');
        $data['enabled'] = 1;
        $this->user->update($request->input('id'),$data);
        return redirect()->route('frontend.profile')->with('success_message','Profile updated');
    }
}