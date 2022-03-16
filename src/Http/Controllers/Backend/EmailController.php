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

use Artisan;
use CoderStudios\CsCms\Http\Controllers\Controller;
use CoderStudios\CsCms\Library\EmailGroupLibrary;
use CoderStudios\CsCms\Library\EmailLibrary;
use CoderStudios\CsCms\Requests\EmailRequest;
use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function __construct(Request $request, Cache $cache, EmailLibrary $emails, EmailGroupLibrary $email_groups)
    {
        $this->email = $emails;
        $this->email_groups = $email_groups;
        $this->attributes = $this->email->getFillable();
        parent::__construct($cache, $request);
    }

    public function index()
    {
        $page_id = 1;
        if ($this->request->get('page')) {
            $page_id = $this->request->get('page');
        }
        $key = $this->key();
        if ($this->useCachedContent($key)) {
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
        $key = $this->key();
        if ($this->useCachedContent($key)) {
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
        $key = $this->key();
        if ($this->useCachedContent($key)) {
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

        return redirect()->route('backend.emails')->with('success', 'Email created');
    }

    public function update(EmailRequest $request, $id = '')
    {
        $data = $request->only($this->attributes);
        $this->email->update($id, $data);
        $email = $this->email->get($id);
        if (count($this->request->request->get('email_groups'))) {
            $email->groups()->sync($this->request->request->get('email_groups'));
        }

        return redirect()->route('backend.emails')->with('success', 'Email updated');
    }

    public function send()
    {
        Artisan::call('email:send');

        return redirect()->route('backend.index')->with('success', 'Emails sent');
    }
}
