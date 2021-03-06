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

class Image {

    /*
    |--------------------------------------------------------------------------
    | Image Class
    |--------------------------------------------------------------------------
    |
    | Manipulate images using PHP
    |
    */

    private $file;

    private $image;

    private $info;

    /**
     *
     * @param  $file path to image file
     *
     * @return Object
     */
    public function setPath($file) {
        if (file_exists($file)) {
            $this->file = $file;

            $info = getimagesize($file);

            $this->info = array(
                'width'  => $info[0],
                'height' => $info[1],
                'bits'   => $info['bits'],
                'mime'   => $info['mime']
            );

            $this->image = $this->create($file);
        } else {
            exit('Error: Could not load image ' . $file . '!');
        }
        return $this;
    }

    /**
     *
     * @param  $image path to image file
     *
     * @return Object
     */
	private function create($image) {
		$mime = $this->info['mime'];

		if ($mime == 'image/gif') {
			return imagecreatefromgif($image);
		} elseif ($mime == 'image/png') {
			return imagecreatefrompng($image);
		} elseif ($mime == 'image/jpeg') {
			return imagecreatefromjpeg($image);
		}
    }

    /**
     * Save a new file based on the file passed
     *
     * @param  $file path to image file
     * @param  $quality default to 100 quality used for jpeg outputs
     *
     * @return
     */
    public function save($file, $quality = 100) {
        $info = pathinfo($file);
        $extension = strtolower($info['extension']);

        if ($extension == 'jpeg' || $extension == 'jpg') {
            imagejpeg($this->image, $file, $quality);
        } elseif($extension == 'png') {
            imagepng($this->image, $file, 0);
        } elseif($extension == 'gif') {
            imagegif($this->image, $file);
        }

	    imagedestroy($this->image);
    }

    /**
     * Resize an image based on dimensions
     *
     * @param  $width new width of image
     * @param  $height new height of image
     *
     * @return
     */
    public function resize($width = 0, $height = 0) {
    	if (!$this->info['width'] || !$this->info['height']) {
			return;
		}

		$xpos = 0;
		$ypos = 0;

		$scale = min($width / $this->info['width'], $height / $this->info['height']);

		if ($scale == 1) {
			return;
		}

		$new_width = (int)($this->info['width'] * $scale);
		$new_height = (int)($this->info['height'] * $scale);
    	$xpos = (int)(($width - $new_width) / 2);
   		$ypos = (int)(($height - $new_height) / 2);

       	$image_old = $this->image;
        $this->image = imagecreatetruecolor($width, $height);

		if (isset($this->info['mime']) && $this->info['mime'] == 'image/png') {
			imagealphablending($this->image, false);
			imagesavealpha($this->image, true);
			$background = imagecolorallocatealpha($this->image, 255, 255, 255, 127);
			imagecolortransparent($this->image, $background);
		} else {
			$background = imagecolorallocate($this->image, 255, 255, 255);
		}

		imagefilledrectangle($this->image, 0, 0, $width, $height, $background);

        imagecopyresampled($this->image, $image_old, $xpos, $ypos, 0, 0, $new_width, $new_height, $this->info['width'], $this->info['height']);
        imagedestroy($image_old);

        $this->info['width']  = $width;
        $this->info['height'] = $height;
    }

    /**
     * Create a watermark image
     *
     * @param  $file filepath to watermark image
     * @param  $position where to position the watermark default bottomright
     *
     * @return
     */
    public function watermark($file, $position = 'bottomright') {
        $watermark = $this->create($file);

        $watermark_width = imagesx($watermark);
        $watermark_height = imagesy($watermark);

        switch($position) {
            case 'topleft':
                $watermark_pos_x = 0;
                $watermark_pos_y = 0;
                break;
            case 'topright':
                $watermark_pos_x = $this->info['width'] - $watermark_width;
                $watermark_pos_y = 0;
                break;
            case 'bottomleft':
                $watermark_pos_x = 0;
                $watermark_pos_y = $this->info['height'] - $watermark_height;
                break;
            case 'bottomright':
                $watermark_pos_x = $this->info['width'] - $watermark_width;
                $watermark_pos_y = $this->info['height'] - $watermark_height;
                break;
        }

        imagecopy($this->image, $watermark, $watermark_pos_x, $watermark_pos_y, 0, 0, 120, 40);

        imagedestroy($watermark);
    }

    /**
     * Crop an image
     *
     * @param  $top_x
     * @param  $top_y
     * @param  $bottom_x
     * @param  $bottom_y
     *
     * @return
     */
    public function crop($top_x, $top_y, $bottom_x, $bottom_y) {
        $image_old = $this->image;
        $this->image = imagecreatetruecolor($bottom_x - $top_x, $bottom_y - $top_y);

        imagecopy($this->image, $image_old, 0, 0, $top_x, $top_y, $this->info['width'], $this->info['height']);
        imagedestroy($image_old);

        $this->info['width'] = $bottom_x - $top_x;
        $this->info['height'] = $bottom_y - $top_y;
    }

    /**
     * Rotate an image
     *
     * @param  $degree
     * @param  $color
     *
     * @return
     */
    public function rotate($degree, $color = 'FFFFFF') {
		$rgb = $this->html2rgb($color);

        $this->image = imagerotate($this->image, $degree, imagecolorallocate($this->image, $rgb[0], $rgb[1], $rgb[2]));

		$this->info['width'] = imagesx($this->image);
		$this->info['height'] = imagesy($this->image);
    }

    /**
     * Filter an image
     *
     * @param  $filter
     *
     * @return
     */
    private function filter($filter) {
        imagefilter($this->image, $filter);
    }

    /**
     * Create text as an image
     *
     * @param  $text
     * @param  $x
     * @param  $y
     * @param  $size
     * @param  $color
     *
     * @return
     */
    private function text($text, $x = 0, $y = 0, $size = 5, $color = '000000') {
		$rgb = $this->html2rgb($color);

		imagestring($this->image, $size, $x, $y, $text, imagecolorallocate($this->image, $rgb[0], $rgb[1], $rgb[2]));
    }

    /**
     * Merge an image with existing image
     *
     * @param  $file
     * @param  $x
     * @param  $y
     * @param  $opacity
     *
     * @return
     */
    private function merge($file, $x = 0, $y = 0, $opacity = 100) {
        $merge = $this->create($file);

        $merge_width = imagesx($image);
        $merge_height = imagesy($image);

        imagecopymerge($this->image, $merge, $x, $y, 0, 0, $merge_width, $merge_height, $opacity);
    }

    /**
     * Convert Html2 to RGB colour
     *
     * @param  $color
     *
     * @return array
     */
	private function html2rgb($color) {
		if ($color[0] == '#') {
			$color = substr($color, 1);
		}

		if (strlen($color) == 6) {
			list($r, $g, $b) = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
		} elseif (strlen($color) == 3) {
			list($r, $g, $b) = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
		} else {
			return FALSE;
		}

		$r = hexdec($r);
		$g = hexdec($g);
		$b = hexdec($b);

		return array($r, $g, $b);
	}
}