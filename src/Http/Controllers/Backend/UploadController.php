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
use CoderStudios\CsCms\Library\UploadLibrary;
use CoderStudios\CsCms\Library\UtilsLibrary;
use CoderStudios\CsCms\Requests\UploadRequest;
use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function __construct(Request $request, Cache $cache, UploadLibrary $upload, UtilsLibrary $utils, Filesystem $file)
    {
        $this->file = $file;
        $this->utils = $utils;
        $this->upload = $upload;
        $this->attributes = $this->upload->getFillable();
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
                'uploads' => $this->upload->getAll($this->request->config['config_items_per_page'], $page_id),
            ];
            $view = view('cscms::backend.pages.uploads', compact('vars'))->render();
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
                'action' => route('backend.uploads.upload.store'),
            ];
            $view = view('cscms::backend.pages.upload-form', compact('vars'))->render();
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
            $upload = $this->upload->get($id);
            $vars = [
                'id' => $id,
                'form_type' => 'edit',
                'action' => route('backend.uploads.upload.update', ['id' => $id]),
            ];
            $view = view('cscms::backend.pages.upload-form', compact('vars'))->render();
            $this->cache->add($key, $view, config('cscms.coderstudios.cache_duration'));
        }

        return $view;
    }

    public function store(UploadRequest $request)
    {
        $file = $request->file('file');
        $json = [];
        $data = [];
        $data['filename'] = $file->getClientOriginalName();
        $data['generated_filename'] = substr(md5($file->getClientOriginalName().date('Y-m-d H:i:s')), 0, 12).'.'.$file->guessExtension();
        $data['filesize'] = $this->utils->convertBytes($file->getClientSize());
        $size = explode(' ', $data['filesize']);
        $data['filesize'] = round($size[0]);
        $data['user_id'] = Auth::user()->id;
        $data['mime'] = '';
        Log::info($data);
        if ($file->isValid()) {
            $upload = $this->upload->create($data);
            $result = $file->move(storage_path('app/uploads'), $data['generated_filename']);
            $json[] = ['result' => true];
        } else {
            $json[] = ['result' => false];
        }
        $failed = $success = 0;
        foreach ($json as $item) {
            if (!$item['result']) {
                ++$failed;
            }
        }
        $success = (count($json) - $failed);
        $message = $success.' '.str_plural('file', $success).' uploaded. <br />';
        if ($failed > 0) {
            $message = $message.' '.$failed.' '.str_plural('file', $failed).' failed to upload.';
        }
        if ($success) {
            $this->request->session()->put('success', $message);
            $this->cache->flush();

            return response()->json(['result' => true, 'path' => route('backend.uploads')]);
        }

        return redirect()->route('backend.uploads')->with('success', $message);
    }

    public function update(UploadRequest $request, $id = '')
    {
        $data = $request->only($this->attributes);
        $data['user_id'] = Auth::user()->id;
        $this->upload->update($id, $data);

        return redirect()->route('backend.uploads')->with('success', 'Upload updated');
    }

    public function delete($id = '')
    {
        $upload = $this->upload->where('id', $id)->first();
        if ($upload) {
            $dir = storage_path().DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'cache';
            if (file_exists($dir.DIRECTORY_SEPARATOR.$upload->generated_filename)) {
                unlink($dir.DIRECTORY_SEPARATOR.$upload->generated_filename);
            }
            $upload->delete();
        }

        return redirect()->route('backend.uploads')->with('success', 'Upload deleted');
    }
}
