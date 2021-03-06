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
 
namespace CoderStudios\CSCMS\Commands;

use Illuminate\Console\Command;
use CoderStudios\CSCMS\Library\Users;
use CoderStudios\CSCMS\Library\Language;
use CoderStudios\CSCMS\Library\Settings;
use CoderStudios\CSCMS\Library\UserRoles;
use CoderStudios\CSCMS\Library\EmailGroup;
use CoderStudios\CSCMS\Library\Capability;

class Reset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cscms:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset the database data';

    /**
     * Create a new command instance.
     *
     * @return void
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
        $this->users->truncate();
        $this->settings->truncate();
        $this->user_roles->truncate();
        $this->email_group->truncate();
        $this->capabilities->truncate();
        $this->language->truncate();
        $this->call('cscms:install');
    }

}