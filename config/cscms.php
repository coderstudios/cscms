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

        'user_roles' => [
            [
                'enabled' => 1,
                'sort_order' => 100,
                'name' => 'Anonymous',
            ],
            [
                'enabled' => 1,
                'sort_order' => 100,
                'name' => 'Admin',
            ],
            [
                'enabled' => 1,
                'sort_order' => 100,
                'name' => 'Member',
            ],
            [
                'enabled' => 1,
                'sort_order' => 100,
                'name' => 'Power user',
            ],
        ],

        'capabilities' => [
            [
                'enabled' => 1,
                'sort_order' => 10,
                'name' => 'view_users',
            ],
            [
                'enabled' => 1,
                'sort_order' => 10,
                'name' => 'create_users',
            ],
            [
                'enabled' => 1,
                'sort_order' => 10,
                'name' => 'edit_users',
            ],
            [
                'enabled' => 1,
                'sort_order' => 10,
                'name' => 'delete_users',
            ],
            [
                'enabled' => 1,
                'sort_order' => 10,
                'name' => 'update_users',
            ],
            [
                'enabled' => 1,
                'sort_order' => 20,
                'name' => 'view_user_roles',
            ],
            [
                'enabled' => 1,
                'sort_order' => 20,
                'name' => 'create_user_roles',
            ],
            [
                'enabled' => 1,
                'sort_order' => 20,
                'name' => 'edit_user_roles',
            ],
            [
                'enabled' => 1,
                'sort_order' => 20,
                'name' => 'delete_user_roles',
            ],
            [
                'enabled' => 1,
                'sort_order' => 20,
                'name' => 'update_user_roles',
            ],
            [
                'enabled' => 1,
                'sort_order' => 30,
                'name' => 'view_settings',
            ],
            [
                'enabled' => 1,
                'sort_order' => 30,
                'name' => 'create_settings',
            ],
            [
                'enabled' => 1,
                'sort_order' => 30,
                'name' => 'edit_settings',
            ],
            [
                'enabled' => 1,
                'sort_order' => 30,
                'name' => 'delete_settings',
            ],
            [
                'enabled' => 1,
                'sort_order' => 30,
                'name' => 'update_settings',
            ],
            [
                'enabled' => 1,
                'sort_order' => 40,
                'name' => 'view_cache',
            ],
            [
                'enabled' => 1,
                'sort_order' => 40,
                'name' => 'create_cache',
            ],
            [
                'enabled' => 1,
                'sort_order' => 40,
                'name' => 'edit_cache',
            ],
            [
                'enabled' => 1,
                'sort_order' => 40,
                'name' => 'delete_cache',
            ],
            [
                'enabled' => 1,
                'sort_order' => 40,
                'name' => 'update_cache',
            ],
            [
                'enabled' => 1,
                'sort_order' => 50,
                'name' => 'view_backups',
            ],
            [
                'enabled' => 1,
                'sort_order' => 50,
                'name' => 'create_backups',
            ],
            [
                'enabled' => 1,
                'sort_order' => 50,
                'name' => 'edit_backups',
            ],
            [
                'enabled' => 1,
                'sort_order' => 50,
                'name' => 'delete_backups',
            ],
            [
                'enabled' => 1,
                'sort_order' => 50,
                'name' => 'update_backups',
            ],
            [
                'enabled' => 1,
                'sort_order' => 60,
                'name' => 'view_capabilities',
            ],
            [
                'enabled' => 1,
                'sort_order' => 60,
                'name' => 'create_capabilities',
            ],
            [
                'enabled' => 1,
                'sort_order' => 60,
                'name' => 'edit_capabilities',
            ],
            [
                'enabled' => 1,
                'sort_order' => 60,
                'name' => 'delete_capabilities',
            ],
            [
                'enabled' => 1,
                'sort_order' => 60,
                'name' => 'update_capabilities',
            ],
            [
                'enabled' => 1,
                'sort_order' => 70,
                'name' => 'view_phpinfo',
            ],
            [
                'enabled' => 1,
                'sort_order' => 80,
                'name' => 'view_export',
            ],
            [
                'enabled' => 1,
                'sort_order' => 80,
                'name' => 'create_export',
            ],
            [
                'enabled' => 1,
                'sort_order' => 90,
                'name' => 'view_import',
            ],
            [
                'enabled' => 1,
                'sort_order' => 90,
                'name' => 'create_import',
            ],
            [
                'enabled' => 1,
                'sort_order' => 100,
                'name' => 'view_emails',
            ],
            [
                'enabled' => 1,
                'sort_order' => 100,
                'name' => 'create_emails',
            ],
            [
                'enabled' => 1,
                'sort_order' => 100,
                'name' => 'edit_emails',
            ],
            [
                'enabled' => 1,
                'sort_order' => 100,
                'name' => 'delete_emails',
            ],
            [
                'enabled' => 1,
                'sort_order' => 100,
                'name' => 'update_emails',
            ],
            [
                'enabled' => 1,
                'sort_order' => 110,
                'name' => 'view_language',
            ],
            [
                'enabled' => 1,
                'sort_order' => 110,
                'name' => 'create_language',
            ],
            [
                'enabled' => 1,
                'sort_order' => 110,
                'name' => 'edit_language',
            ],
            [
                'enabled' => 1,
                'sort_order' => 110,
                'name' => 'delete_language',
            ],
            [
                'enabled' => 1,
                'sort_order' => 110,
                'name' => 'update_language',
            ],
        ],

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
