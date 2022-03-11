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

use Auth;
use CoderStudios\CsCms\Http\Controllers\Controller;
use CoderStudios\CsCms\Library\Notifications;
use CoderStudios\CsCms\Library\NotificationsRead;
use CoderStudios\CsCms\Requests\NotificationRequest;
use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(Request $request, Cache $cache, Notifications $notifications, NotificationsRead $nr)
    {
        $this->nr = $nr;
        $this->notifications = $notifications;
        $this->attributes = $this->notifications->getFillable();
        parent::__construct($cache, $request);
    }

    public function index()
    {
        $page_id = 1;
        if ($this->request->get('page')) {
            $page_id = $this->request->get('page');
        }
        $key = md5(snake_case(str_replace('\\', '', __NAMESPACE__).class_basename($this).'_'.__FUNCTION__.'_'.$page_id));
        if ($this->useCachedContent($key)) {
            $view = $this->cache->get($key);
        } else {
            $vars = [
                'notifications' => $this->notifications->getAll($this->request->config['config_items_per_page'], $page_id),
            ];
            $view = view('cscms::backend.pages.notifications', compact('vars'))->render();
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
                'action' => route('backend.notifications.store'),
                'notification' => $this->notifications->newInstance(),
            ];
            $view = view('cscms::backend.pages.notifications-form', compact('vars'))->render();
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
            $notification = $this->notifications->get($id);
            $vars = [
                'form_type' => 'edit',
                'action' => route('backend.notifications.notification.update', ['id' => $id]),
                'notification' => $notification,
            ];
            $view = view('cscms::backend.pages.notifications-form', compact('vars'))->render();
            $this->cache->add($key, $view, config('cscms.coderstudios.cache_duration'));
        }

        return $view;
    }

    public function store(NotificationRequest $request, $id = '')
    {
        $data = $request->only($this->attributes);
        $data['user_id'] = Auth::user()->id;
        $notification = $this->notifications->create($data);
        $this->cache->flush();

        return redirect()->route('backend.notifications')->with('success_message', 'Notification created');
    }

    public function update(NotificationRequest $request, $id = '')
    {
        $data = $request->only($this->attributes);
        $data['user_id'] = Auth::user()->id;
        $this->notifications->update($id, $data);
        $this->cache->flush();

        return redirect()->route('backend.notifications')->with('success_message', 'Notification updated');
    }
}
