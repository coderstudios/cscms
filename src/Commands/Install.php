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

namespace CoderStudios\CsCms\Commands;

use CoderStudios\CsCms\Library\CapabilityLibrary;
use CoderStudios\CsCms\Library\EmailGroupLibrary;
use CoderStudios\CsCms\Library\LanguageLibrary;
use CoderStudios\CsCms\Library\SettingsLibrary;
use CoderStudios\CsCms\Library\UserRolesLibrary;
use CoderStudios\CsCms\Library\UsersLibrary;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

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
    public function __construct(SettingsLibrary $settings, UserRolesLibrary $user_roles, EmailGroupLibrary $email_group, CapabilityLibrary $capabilities, UsersLibrary $user, LanguageLibrary $language)
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
        $email_groups = $capabilities = $language = [];

        foreach (config('cscms.coderstudios.settings') as $setting) {
            $this->settings->create($setting);
        }

        foreach (config('cscms.coderstudios.user_roles') as $user_role) {
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

        foreach (config('cscms.coderstudios.capabilities') as $capability) {
            $this->capabilities->create($capability);
        }

        $role = $this->user_roles->where('id', 2)->first();
        $role->capabilities()->sync($this->capabilities->pluck('id'));

        $this->info('Lets setup your admin account...');
        $email = $this->ask('What is the admin email going to be?');
        $name = $this->ask('What is the admin name?');
        $password = $this->secret('What is the password?');

        if (!empty($email) && !empty($name) && !empty($password)) {
            $user = $this->users->create([
                'email' => $email,
                'name' => $name,
                'username' => 'admin',
                'password' => Hash::make($password),
                'enabled' => 1,
                'verified' => 1,
                'user_role_id' => 2,
            ]);

            $this->info('Great thanks, account setup. Login with your email and the password you entered at: '.route('login'));
        } else {
            $this->info('You need to enter appropriate information for each question, please re run the install!');
        }
    }
}
