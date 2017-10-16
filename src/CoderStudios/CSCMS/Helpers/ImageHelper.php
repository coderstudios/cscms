<?php
/**
 * Part of the CSCMS package by Coder Studios.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the terms of the MIT license https://opensource.org/licenses/MIT
 *
 * @package    CSCMS
 * @version    1.0.0
 * @author     Coder Studios Ltd
 * @license    MIT https://opensource.org/licenses/MIT
 * @copyright  (c) 2017, Coder Studios Ltd
 * @link       https://www.coderstudios.com
 */
 
namespace CoderStudios\CSCMS\Helpers;

use CoderStudios\Helpers\Utf8;
use CoderStudios\Helpers\Image;

class ImageHelper {

	/*
    |--------------------------------------------------------------------------
    | Image Helper Class
    |--------------------------------------------------------------------------
    |
    | Interfaces with the Image class to resize images creating a 'cache' version at the specified
    | dimensions
    |
    */

	protected $utf8;

	protected $image;

	protected $base;

	public function __construct(Utf8 $utf8, Image $image) {
		$this->utf8 = $utf8;
		$this->image = $image;
		$this->base = storage_path() . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
	}

    /**
     * Resize a given image and save new resized image to cache folder for reuse later
     *
     * @param  $directory_path path to image file
     * @param  $filename name of the file
     * @param  $width Width of the new image
     * @param  $height Height of the new image
     *
     * @return String
     */
	public function resize( $directory_path , $filename , $width , $height )
	{

		if (!is_file($this->base . $directory_path . DIRECTORY_SEPARATOR . $filename)) {
			return;
		}

		$extension = pathinfo($filename, PATHINFO_EXTENSION);

		$old_image = $filename;
		$new_image = $directory_path . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . $this->utf8->utf8_substr($filename, 0, $this->utf8->utf8_strrpos($filename, '.')) . '-' . $width . 'x' . $height . '.' . $extension;

		if (!file_exists($this->base . $new_image) || (filectime($this->base . $directory_path . DIRECTORY_SEPARATOR . $old_image) > filectime($this->base . $new_image))) {
			$path = '';

			$directories = explode('/', dirname(str_replace('../', '', $new_image)));

			foreach ($directories as $directory) {
				$path = $path . DIRECTORY_SEPARATOR . $directory;

				if (!file_exists(storage_path() . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'images' . $path)) {
					@mkdir(storage_path() . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'images' . $path, 0777);
				}
			}

			$image = $this->image->setPath($this->base . $directory_path . DIRECTORY_SEPARATOR . $old_image);
			$image->resize($width, $height);
			$image->save($this->base . $new_image);
		}

		return $this->base . $new_image;
	}
}