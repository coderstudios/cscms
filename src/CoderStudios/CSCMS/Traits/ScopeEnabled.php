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
 * @copyright  (c) 2022, Coder Studios Ltd
 * @link       https://www.coderstudios.com
 */
 
namespace CoderStudios\CSCMS\Traits;

trait ScopeEnabled {

    /**
     * Enabled filter
     * @param  $query
     * @param  value
     * @return collection
     */
    public function scopeEnabled($query, $enabled = 1)
    {
        $query->where('enabled','=',$enabled);
    }
}
