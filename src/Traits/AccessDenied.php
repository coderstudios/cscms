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

namespace CoderStudios\CsCms\Traits;

trait AccessDenied
{
    public $access_denied_redirect = '/';
    public $access_denied_message = 'Resource not found';

    public function accessDenied()
    {
        return redirect()->to($this->access_denied_redirect)->with('error', $this->access_denied_message);
    }
}
