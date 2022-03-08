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

namespace CoderStudios\CsCms\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use CoderStudios\CsCms\Helpers\ImageHelper;
use CoderStudios\CsCms\Library\Image;
use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ImageController extends Controller
{
    public function __construct(Request $request, Cache $cache, Image $image, ImageHelper $imageHelper, Filesystem $file)
    {
        $this->file = $file;
        $this->image = $image;
        $this->request = $request;
        $this->image_helper = $imageHelper;
        $this->cache = $cache->store('backend_views');
        $this->attributes = $this->image->getFillable();
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
