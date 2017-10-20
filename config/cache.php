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
 
return [

    /*
    |--------------------------------------------------------------------------
    | Coder Studios CMS variables
    |--------------------------------------------------------------------------
    |
    |
    |
    |
    */

    'frontend_views' => [
        'driver' => 'file',
        'path' => storage_path('framework/cache/data/frontend'),
    ],

    'backend_views' => [
        'driver' => 'file',
        'path' => storage_path('framework/cache/data/backend'),
    ],

    'models' => [
        'driver' => 'file',
        'path' => storage_path('framework/cache/data/models'),
    ],
];