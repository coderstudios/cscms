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

namespace CoderStudios\CSCMS\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Artisan;
use CoderStudios\CSCMS\Library\Email;
use CoderStudios\CSCMS\Library\EmailGroup;
use CoderStudios\CSCMS\Requests\EmailRequest;
use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function __construct(Request $request, Cache $cache, Email $emails, EmailGroup $email_groups)
    {
        $this->email = $emails;
        $this->request = $request;
        $this->email_groups = $email_groups;
        $this->cache = $cache->store('backend_views');
        $this->attributes = $this->email->getFillable();
    }

    public function index()
    {
        $page_id = 1;
        if ($this->request->get('page')) {
            $page_id = $this->request->get('page');
        }
        $key = md5(snake_case(str_replace('\\', '', __NAMESPACE__).class_basename($this).'_'.__FUNCTION__.'_'.$page_id));
        if ($this->cache->has($key)) {
            $view = $this->cache->get($key);
        } else {
            $vars = [
                'emails' => $this->email->getAll($this->request->config['config_items_per_page'], $page_id),
            ];
            $view = view('cscms::backend.pages.emails', compact('vars'))->render();
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
                'form_type' => 'create',
                'action' => route('backend.emails.store'),
                'email' => $this->email->newInstance(),
                'groups' => $this->email_groups->getAll(0),
            ];
            $view = view('cscms::backend.pages.emails-form', compact('vars'))->render();
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
            $email = $this->email->get($id);
            $vars = [
                'form_type' => 'edit',
                'action' => route('backend.emails.email.update', ['id' => $id]),
                'email' => $email,
                'groups' => $this->email_groups->getAll(0),
            ];
            $view = view('cscms::backend.pages.emails-form', compact('vars'))->render();
            $this->cache->add($key, $view, config('cscms.coderstudios.cache_duration'));
        }

        return $view;
    }

    public function store(EmailRequest $request, $id = '')
    {
        $data = $request->only($this->attributes);
        $email = $this->email->create($data);
        if (count($this->request->request->get('email_groups'))) {
            $email->groups()->sync($this->request->request->get('email_groups'));
        }

        return redirect()->route('backend.emails')->with('success_message', 'Email created');
    }

    public function update(EmailRequest $request, $id = '')
    {
        $data = $request->only($this->attributes);
        $this->email->update($id, $data);
        $email = $this->email->get($id);
        if (count($this->request->request->get('email_groups'))) {
            $email->groups()->sync($this->request->request->get('email_groups'));
        }

        return redirect()->route('backend.emails')->with('success_message', 'Email updated');
    }

    public function send()
    {
        Artisan::call('email:send');

        return redirect()->route('backend.index')->with('success_message', 'Emails sent');
    }
}
