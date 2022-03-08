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

namespace CoderStudios\CsCms\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Artisan;
use CoderStudios\CsCms\Library\Mail;
use CoderStudios\CsCms\Library\UserRoles;
use CoderStudios\CsCms\Library\Users;
use CoderStudios\CsCms\Requests\UpdateUserRequest;
use CoderStudios\CsCms\Requests\UserRequest;
use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(Request $request, Cache $cache, Users $users, UserRoles $user_roles, Mail $mail)
    {
        $this->mail = $mail;
        $this->users = $users;
        $this->request = $request;
        $this->user_roles = $user_roles;
        $this->cache = $cache->store('backend_views');
        $this->attributes = $this->users->getFillable();
    }

    public function index()
    {
        $page_id = 1;
        if ($this->request->get('page')) {
            $page_id = $this->request->get('page');
        }
        $key = md5(snake_case(str_replace('\\', '', __NAMESPACE__).class_basename($this).'_'.__FUNCTION__.'_'.$page_id));
        $this->request->session()->put('key', $key);
        if ($this->cache->has($key)) {
            $view = $this->cache->get($key);
        } else {
            $vars = [
                'users' => $this->users->getAll($this->request->config['config_items_per_page'], $page_id),
            ];
            $view = view('cscms::backend.pages.users', compact('vars'))->render();
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
                'action' => route('backend.users.store'),
                'user' => $this->users->newInstance(),
                'user_roles' => $this->user_roles->getEnabled(1, 0),
            ];
            $view = view('cscms::backend.pages.users-form', compact('vars'))->render();
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
            $vars = [
                'id' => $id,
                'form_type' => 'edit',
                'action' => route('backend.users.user.update', ['username' => $id]),
                'user' => $this->users->get($id),
                'user_roles' => $this->user_roles->getEnabled(1, 0),
            ];
            $view = view('cscms::backend.pages.users-form', compact('vars'))->render();
            $this->cache->add($key, $view, config('cscms.coderstudios.cache_duration'));
        }

        return $view;
    }

    public function store(UserRequest $request)
    {
        $data = $request->only('name', 'email', 'enabled', 'password', 'username', 'user_role_id');
        $data['password'] = bcrypt($data['password']);
        $this->users->create($data);
        Artisan::call('cache:clear');

        return redirect()->route('backend.users')->with('success_message', 'User created');
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $data = $request->only('name', 'email', 'enabled', 'password', 'username', 'user_role_id');
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }
        if (empty($data['enabled'])) {
            $data['enabled'] = 0;
        }
        $this->users->update($id, $data);
        Artisan::call('cache:clear');

        return redirect()->route('backend.users')->with('success_message', 'User updated');
    }

    public function view()
    {
    }

    public function delete($id)
    {
        $this->users->delete($id);
        Artisan::call('cache:clear');

        return redirect()->route('backend.users')->with('success_message', 'User deleted');
    }

    public function resendVerify($id = '')
    {
        $user = $this->users->where('id', $id)->first();
        $this->mail->where('subject', sprintf('Verify your account on %s', config('app.name')))->where('to_email', $user->email)->update(['resend' => '1', 'enabled' => '1']);

        return redirect()->route('backend.users');
    }
}
