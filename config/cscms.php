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

        'settings' => [
            [
                'name' => 'user_verify_users',
                'value' => '1',
            ],
            [
                'name' => 'user_require_username',
                'value' => '1',
            ],
            [
                'name' => 'user_allow_registration',
                'value' => '1',
            ],
            [
                'name' => 'mail_mail_from_address',
                'value' => 'example@example.com',
            ],
            [
                'name' => 'mail_mail_from_name',
                'value' => 'example',
            ],
            [
                'name' => 'mail_mail_domain',
                'value' => 'example.com',
            ],
            [
                'name' => 'mail_mail_secret',
                'value' => 'secret',
            ],
            [
                'name' => 'mail_mail_driver',
                'value' => 'smtp',
            ],
            [
                'name' => 'mail_mail_encryption',
                'value' => 'tls',
            ],
            [
                'name' => 'mail_mail_enabled',
                'value' => '1',
            ],
            [
                'name' => 'config_contact_email',
                'value' => 'example@example.com',
            ],
            [
                'name' => 'config_items_per_page',
                'value' => 25,
            ],
        ],
    ],
];
