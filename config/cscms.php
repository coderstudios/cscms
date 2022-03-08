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

    'coderstudios' => [
        'cache_duration' => env('APP_CACHE_MINUTES', 240),

        'cache_enabled' => env('APP_CACHE', true),

        'backup_dir' => env('APP_BACKUP_DIR', storage_path().'/app/dumps'),

        'theme' => env('APP_THEME', 'default'),

        'backend_prefix' => env('APP_BACKEND_PREFIX', '_admin'),

        'frontend_prefix' => env('APP_FRONTEND_PREFIX', ''),
    ],
];
