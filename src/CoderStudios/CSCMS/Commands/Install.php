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

namespace CoderStudios\CsCms\Commands;

use CoderStudios\CsCms\Library\Capability;
use CoderStudios\CsCms\Library\EmailGroup;
use CoderStudios\CsCms\Library\Language;
use CoderStudios\CsCms\Library\Settings;
use CoderStudios\CsCms\Library\UserRoles;
use CoderStudios\CsCms\Library\Users;
use Illuminate\Console\Command;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cscms:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup the initial database data';

    /**
     * Create a new command instance.
     */
    public function __construct(Settings $settings, UserRoles $user_roles, EmailGroup $email_group, Capability $capabilities, Users $user, Language $language)
    {
        parent::__construct();
        $this->users = $user;
        $this->settings = $settings;
        $this->language = $language;
        $this->user_roles = $user_roles;
        $this->email_group = $email_group;
        $this->capabilities = $capabilities;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $settings = $user_roles = $email_groups = $capabilities = $language = [];

        $settings[] = [
            'name' => 'user_verify_users',
            'class' => 'user',
            'value' => '1',
            'serialized' => 0,
        ];

        $settings[] = [
            'name' => 'user_require_username',
            'class' => 'user',
            'value' => '1',
            'serialized' => 0,
        ];

        $settings[] = [
            'name' => 'user_allow_registration',
            'class' => 'user',
            'value' => '1',
            'serialized' => 0,
        ];

        $settings[] = [
            'name' => 'mail_from_address',
            'class' => 'mail',
            'value' => 'example@example.com',
            'serialized' => 0,
        ];

        $settings[] = [
            'name' => 'mail_from_name',
            'class' => 'mail',
            'value' => 'Example',
            'serialized' => 0,
        ];

        $settings[] = [
            'name' => 'mail_mailgun_domain',
            'class' => 'mail',
            'value' => 'example.com',
            'serialized' => 0,
        ];

        $settings[] = [
            'name' => 'mail_mailgun_secret',
            'class' => 'mail',
            'value' => '123456789',
            'serialized' => 0,
        ];

        $settings[] = [
            'name' => 'mail_mail_driver',
            'class' => 'mail',
            'value' => 'mailgun',
            'serialized' => 0,
        ];

        $settings[] = [
            'name' => 'mail_mail_encryption',
            'class' => 'mail',
            'value' => 'tls',
            'serialized' => 0,
        ];

        $settings[] = [
            'name' => 'mail_mail_enabled',
            'class' => 'mail',
            'value' => '0',
            'serialized' => 0,
        ];

        $settings[] = [
            'name' => 'config_contact_email',
            'class' => 'config',
            'value' => 'example@example.com',
            'serialized' => 0,
        ];

        $settings[] = [
            'name' => 'config_items_per_page',
            'class' => 'config',
            'value' => '25',
            'serialized' => 0,
        ];

        foreach ($settings as $setting) {
            $this->settings->create($setting);
        }

        $user_roles[] = [
            'enabled' => 1,
            'sort_order' => 0,
            'name' => 'Anonymous',
        ];

        $user_roles[] = [
            'enabled' => 1,
            'sort_order' => 0,
            'name' => 'Admin',
        ];

        $user_roles[] = [
            'enabled' => 1,
            'sort_order' => 0,
            'name' => 'Member',
        ];

        $user_roles[] = [
            'enabled' => 1,
            'sort_order' => 0,
            'name' => 'Power user',
        ];

        foreach ($user_roles as $user_role) {
            $this->user_roles->create($user_role);
        }

        $email_groups[] = [
            'enabled' => 1,
            'sort_order' => 0,
            'name' => 'Newsletter',
        ];

        foreach ($email_groups as $email_group) {
            $this->email_group->create($email_group);
        }

        $language[] = [
            'enabled' => 1,
            'sort_order' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'code' => 'en-gb',
            'name' => 'English',
            'locale' => 'en-US,en_US.UTF-8,en-gb,english',
        ];

        foreach ($language as $l) {
            $this->language->create($l);
        }

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 0,
            'name' => 'view_users',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 0,
            'name' => 'create_users',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 0,
            'name' => 'edit_users',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 0,
            'name' => 'delete_users',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 0,
            'name' => 'update_users',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 1,
            'name' => 'view_user_roles',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 1,
            'name' => 'create_user_roles',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 1,
            'name' => 'edit_user_roles',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 1,
            'name' => 'delete_user_roles',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 1,
            'name' => 'update_user_roles',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 2,
            'name' => 'view_settings',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 2,
            'name' => 'create_settings',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 2,
            'name' => 'edit_settings',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 2,
            'name' => 'delete_settings',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 2,
            'name' => 'update_settings',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 3,
            'name' => 'view_cache',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 3,
            'name' => 'create_cache',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 3,
            'name' => 'edit_cache',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 3,
            'name' => 'delete_cache',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 3,
            'name' => 'update_cache',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 4,
            'name' => 'view_backups',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 4,
            'name' => 'create_backups',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 4,
            'name' => 'edit_backups',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 4,
            'name' => 'delete_backups',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 4,
            'name' => 'update_backups',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 5,
            'name' => 'view_capabilities',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 5,
            'name' => 'create_capabilities',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 5,
            'name' => 'edit_capabilities',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 5,
            'name' => 'delete_capabilities',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 5,
            'name' => 'update_capabilities',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 6,
            'name' => 'view_phpinfo',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 7,
            'name' => 'view_export',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 7,
            'name' => 'create_export',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 8,
            'name' => 'view_import',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 8,
            'name' => 'create_import',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 9,
            'name' => 'view_emails',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 9,
            'name' => 'create_emails',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 9,
            'name' => 'edit_emails',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 9,
            'name' => 'delete_emails',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 9,
            'name' => 'update_emails',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 10,
            'name' => 'view_language',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 10,
            'name' => 'create_language',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 10,
            'name' => 'edit_language',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 10,
            'name' => 'delete_language',
        ];

        $capabilities[] = [
            'enabled' => 1,
            'sort_order' => 10,
            'name' => 'update_language',
        ];

        foreach ($capabilities as $capability) {
            $this->capabilities->create($capability);
        }

        $role = $this->user_roles->where('id', 2)->first();
        $role->capabilities()->sync($this->capabilities->pluck('id'));

        $this->info('Lets setup your admin account...');
        $email = $this->ask('What is the admin email going to be?');
        $name = $this->ask('What is the admin name?');
        $password = $this->secret('What is the password?');

        if (!empty($email) && !empty($name) && !empty($password)) {
            $this->users->create([
                'email' => $email,
                'name' => $name,
                'username' => 'admin',
                'password' => bcrypt($password),
                'enabled' => 1,
                'verified' => 1,
                'user_role_id' => 2,
            ]);
            $this->info('Great thanks, account setup. Login via: '.route('backend.login'));
        } else {
            $this->info('You need to enter appropriate information for each question, please re run the install!');
        }
    }
}
