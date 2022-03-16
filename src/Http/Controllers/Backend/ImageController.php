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
use CoderStudios\CsCms\Helpers\ImageHelper;
use CoderStudios\CsCms\Http\Controllers\Controller;
use CoderStudios\CsCms\Library\ImageLibrary;
use CoderStudios\CsCms\Library\UtilsLibrary;
use CoderStudios\CsCms\Requests\ImageRequest;
use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ImageController extends Controller
{
    public function __construct(Request $request, Cache $cache, ImageLibrary $image, UtilsLibrary $utils, ImageHelper $imageHelper, Filesystem $file)
    {
        $this->file = $file;
        $this->image = $image;
        $this->utils = $utils;
        $this->image_helper = $imageHelper;
        $this->attributes = $this->image->getFillable();
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
            $images = $this->image->getAll([
                'size' => $this->request->config['config_items_per_page'],
                'page' => $page_id,
                'order' => 'id',
                'dir' => 'DESC',
            ]);
            $vars = [
                'images' => $images,
            ];
            $view = view('cscms::backend.pages.images', compact('vars'))->render();
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
                'action' => route('backend.images.image.store'),
            ];
            $view = view('cscms::backend.pages.images-form', compact('vars'))->render();
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
            $image = $this->image->get($id);
            $vars = [
                'id' => $id,
                'form_type' => 'edit',
                'action' => route('backend.images.image.update', ['id' => $id]),
            ];
            $view = view('cscms::backend.pages.images-form', compact('vars'))->render();
            $this->cache->add($key, $view, config('cscms.coderstudios.cache_duration'));
        }

        return $view;
    }

    public function store(ImageRequest $request)
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
        if ($file->isValid()) {
            $upload = $this->image->create($data);
            $result = $file->move(storage_path('app/images'), $data['generated_filename']);
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

            return response()->json(['result' => true, 'path' => route('backend.images')]);
        }
        $this->cache->flush();

        return redirect()->route('backend.images')->with('success', $message);
    }

    public function update(ImageRequest $request, $id = '')
    {
        $data = $request->only($this->attributes);
        $data['user_id'] = Auth::user()->id;
        $this->image->update($id, $data);

        return redirect()->route('backend.images')->with('success', 'Image updated');
    }

    public function delete($id = '')
    {
        $image = $this->image->where('id', $id)->first();
        if ($image) {
            $dir = storage_path().DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'cache';
            if (file_exists($dir.DIRECTORY_SEPARATOR.$image->generated_filename)) {
                unlink($dir.DIRECTORY_SEPARATOR.$image->generated_filename);
            }
            $image->delete();
        }
        $this->cache->flush();

        return redirect()->route('backend.images')->with('success', 'Image deleted');
    }

    public function render()
    {
        $user_id = '';
        $width = 100;
        $height = 100;
        $filename = '';
        $folder = '';

        if ($this->request->input('filename')) {
            $filename = $this->request->input('filename');
        }

        if ($this->request->input('user_id')) {
            $user_id = $this->request->input('user_id');
        }

        if (empty($user_id) && $this->request->input('id')) {
            $user_id = $this->request->input('id');
        }

        if ($this->request->input('width')) {
            $width = $this->request->input('width');
        }

        if ($this->request->input('height')) {
            $height = $this->request->input('height');
        }

        $image = $this->image_helper->resize($folder, $filename, $width, $height);

        if (!$image && !empty($user_id)) {
            $image = $this->image_helper->resize($user_id, $filename, $width, $height);
        }

        $info = pathinfo($image);
        $extension = isset($info['extension']) ? $info['extension'] : 'png';
        $image = $this->file->get($image);

        return (new Response($image, 200))
            ->header('Content-Type', 'image/'.$extension)
        ;
    }
}
