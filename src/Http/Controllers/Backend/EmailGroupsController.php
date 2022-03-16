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
use CoderStudios\CsCms\Library\EmailGroupLibrary;
use CoderStudios\CsCms\Requests\EmailGroupRequest;
use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Http\Request;

class EmailGroupsController extends Controller
{
    public function __construct(Request $request, Cache $cache, EmailGroupLibrary $email_groups)
    {
        $this->email_group = $email_groups;
        $this->attributes = $this->email_group->getFillable();
        parent::__construct($cache, $request);
    }

    public function index()
    {
        $page_id = $this->getPage();
        $key = $this->key();
        if ($this->useCachedContent($key)) {
            $view = $this->cache->get($key);
        } else {
            $vars = [
                'email_groups' => $this->email_group->getAll($this->request->config['config_items_per_page'], $page_id),
            ];
            $view = view('cscms::backend.pages.email_groups', compact('vars'))->render();
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
                'form_type' => 'create',
                'action' => route('backend.email_groups.store'),
                'email_group' => $this->email_group->newInstance(),
            ];
            $view = view('cscms::backend.pages.email_groups-form', compact('vars'))->render();
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
            $email_group = $this->email_group->get($id);
            $vars = [
                'form_type' => 'edit',
                'action' => route('backend.email_groups.email_group.update', ['id' => $id]),
                'email_group' => $email_group,
            ];
            $view = view('cscms::backend.pages.email_groups-form', compact('vars'))->render();
            $this->cache->add($key, $view, config('cscms.coderstudios.cache_duration'));
        }

        return $view;
    }

    public function store(EmailGroupRequest $request, $id = '')
    {
        $data = $request->only($this->attributes);
        $this->email_group->create($data);

        return redirect()->route('backend.email_groups')->with('success', 'Email Group created');
    }

    public function update(EmailGroupRequest $request, $id = '')
    {
        $data = $request->only($this->attributes);
        $this->email_group->update($id, $data);

        return redirect()->route('backend.email_groups')->with('success', 'Email Group updated');
    }

    public function delete($id)
    {
        $this->email_group->delete($id);

        return redirect()->route('backend.email_groups')->with('success', 'Email Group deleted');
    }
}
