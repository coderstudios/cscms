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
use CoderStudios\CsCms\Library\Settings;
use CoderStudios\CsCms\Requests\SettingRequest;
use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function __construct(Request $request, Cache $cache, Settings $setting)
    {
        $this->setting = $setting;
        $this->request = $request;
        $this->cache = $cache->store(config('cache.default'));
        $this->attributes = $this->setting->getFillable();
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
                'settings' => $this->setting->getAll($this->request->config['config_items_per_page'], $page_id),
            ];
            $view = view('cscms::backend.pages.settings', compact('vars'))->render();
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
                'action' => route('backend.settings.setting.store'),
                'setting' => $this->setting->newInstance(),
            ];
            $view = view('cscms::backend.pages.settings-form', compact('vars'))->render();
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
            $setting = $this->setting->get($id);
            if ($setting->serialized) {
                $setting->value = unserialize($setting->value);
            }
            $vars = [
                'form_type' => 'edit',
                'action' => route('backend.settings.setting.update', ['id' => $id]),
                'setting' => $setting,
            ];
            $view = view('cscms::backend.pages.settings-form', compact('vars'))->render();
            $this->cache->add($key, $view, config('cscms.coderstudios.cache_duration'));
        }

        return $view;
    }

    public function store(SettingRequest $request, $id = '')
    {
        $data = $request->only($this->attributes);
        $data = $this->setSerialized($data);
        $this->setting->create($data);
        $this->cache->flush();

        return redirect()->route('backend.settings')->with('success_message', 'Setting created');
    }

    public function update(SettingRequest $request, $id = '')
    {
        $data = $request->only($this->attributes);
        $data = $this->setSerialized($data);
        $this->setting->update($id, $data);
        $this->cache->flush();

        return redirect()->route('backend.settings')->with('success_message', 'Setting updated');
    }

    public function delete($id)
    {
        $this->setting->delete($id);
        $this->cache->flush();

        return redirect()->route('backend.settings')->with('success_message', 'Setting deleted');
    }

    private function setSerialized($data)
    {
        if (!isset($data['serialized'])) {
            $data['serialized'] = 0;
        } else {
            $data['value'] = serialize($data['value']);
        }

        return $data;
    }
}
