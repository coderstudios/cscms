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
use CoderStudios\CsCms\Library\CapabilityLibrary;
use CoderStudios\CsCms\Library\UserRolesLibrary;
use CoderStudios\CsCms\Requests\UserRoleRequest;
use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Http\Request;

class UserRolesController extends Controller
{
    public function __construct(Request $request, Cache $cache, UserRolesLibrary $user_roles, CapabilityLibrary $capabilities)
    {
        $this->user_roles = $user_roles;
        $this->capabilities = $capabilities;
        $this->attributes = $this->user_roles->getFillable();
        parent::__construct($cache, $request);
    }

    public function index()
    {
        $page_id = $this->getPage();
        $key = $this->key();
        $this->request->session()->put('key', $key);
        if ($this->useCachedContent($key)) {
            $view = $this->cache->get($key);
        } else {
            $vars = [
                'user_roles' => $this->user_roles->paginate($this->request->config['config_items_per_page']),
            ];
            $view = view('cscms::backend.pages.user_roles', compact('vars'))->render();
            $this->cache->add($key, $view, config('cscms.coderstudios.cache_duration'));
        }

        return $view;
    }

    public function create()
    {
        $key = $this->key();
        if ($this->useCachedContent($key)) {
            $view = $this->cache->get($key);
        } else {
            $vars = [
                'id' => '',
                'form_type' => 'create',
                'action' => route('backend.user_roles.store'),
                'user_role' => $this->user_roles->newInstance(),
                'capabilities' => $this->capabilities->getEnabled(1, 0),
            ];
            $view = view('cscms::backend.pages.user_roles-form', compact('vars'))->render();
            $this->cache->add($key, $view, config('cscms.coderstudios.cache_duration'));
        }

        return $view;
    }

    public function edit($id = '')
    {
        $key = $this->key();
        if ($this->useCachedContent($key)) {
            $view = $this->cache->get($key);
        } else {
            $vars = [
                'id' => $id,
                'form_type' => 'edit',
                'action' => route('backend.user_roles.user_role.update', ['id' => $id]),
                'user_role' => $this->user_roles->get($id),
                'capabilities' => $this->capabilities->getEnabled(1, 0),
            ];
            $view = view('cscms::backend.pages.user_roles-form', compact('vars'))->render();
            $this->cache->add($key, $view, config('cscms.coderstudios.cache_duration'));
        }

        return $view;
    }

    public function store(UserRoleRequest $request)
    {
        $data = $request->only($this->attributes);
        $role = $this->user_roles->create($data);
        if (count($this->request->request->get('capabilities'))) {
            $role->capabilities()->sync($this->request->request->get('capabilities'));
        }
        $this->cache->flush();

        return redirect()->route('backend.user_roles')->with('success', 'User role created');
    }

    public function update(UserRoleRequest $request, $id)
    {
        $data = $request->only($this->attributes);
        if (empty($data['enabled'])) {
            $data['enabled'] = 0;
        }
        $this->user_roles->update($id, $data);
        $role = $this->user_roles->get($id);
        if (count($this->request->request->get('capabilities'))) {
            $role->capabilities()->sync($this->request->request->get('capabilities'));
        }
        $this->cache->flush();

        return redirect()->route('backend.user_roles')->with('success', 'User role updated');
    }

    public function delete($id)
    {
        $this->user_roles->delete($id);
        $this->cache->flush();

        return redirect()->route('backend.user_roles')->with('success', 'User role deleted');
    }
}
