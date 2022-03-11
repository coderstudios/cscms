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

namespace CoderStudios\Traits;

trait NotFound
{
    public $not_found_redirect = '/';
    public $not_found_message = 'Resource not found';

    public function notFound()
    {
        return redirect()->to($this->not_found_redirect)->with('error', $this->not_found_message);
    }
}
