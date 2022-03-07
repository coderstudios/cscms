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

namespace CoderStudios\CSCMS\Policies;

use CoderStudios\CSCMS\Models\User;
use Illuminate\Support\Facades\Gate;
use CoderStudios\CSCMS\Models\Setting;
use CoderStudios\CSCMS\Models\Language;
use CoderStudios\CSCMS\Models\UserTypes;
use CoderStudios\CSCMS\Models\Capability;
use CoderStudios\CSCMS\Policies\UsersPolicy;
use CoderStudios\CSCMS\Policies\SettingPolicy;
use CoderStudios\CSCMS\Policies\LanguagePolicy;
use CoderStudios\CSCMS\Policies\UserTypesPolicy;
use CoderStudios\CSCMS\Policies\CapabilityPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class PolicyProvider extends ServiceProvider
{

    protected $policies = [
        UserTypes::class => UserTypesPolicy::class,
        Capability::class => CapabilityPolicy::class,
        User::class => UsersPolicy::class,
        Setting::class => SettingPolicy::class,
        Language::class => LanguagePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('view_cache', function ($user, $id) {
            return in_array($id,$user->role->capabilities()->get()->pluck('id')->toArray());
        });
        Gate::define('update_cache', function ($user, $id) {
            return in_array($id,$user->role->capabilities()->get()->pluck('id')->toArray());
        });
        Gate::define('view_backups', function ($user, $id) {
            return in_array($id,$user->role->capabilities()->get()->pluck('id')->toArray());
        });
        Gate::define('create_backups', function ($user, $id) {
            return in_array($id,$user->role->capabilities()->get()->pluck('id')->toArray());
        });
        Gate::define('delete_backups', function ($user, $id) {
            return in_array($id,$user->role->capabilities()->get()->pluck('id')->toArray());
        });
        Gate::define('view_phpinfo', function ($user, $id) {
            return in_array($id,$user->role->capabilities()->get()->pluck('id')->toArray());
        });
        Gate::define('view_export', function ($user, $id) {
            return in_array($id,$user->role->capabilities()->get()->pluck('id')->toArray());
        });
        Gate::define('create_export', function ($user, $id) {
            return in_array($id,$user->role->capabilities()->get()->pluck('id')->toArray());
        });
    }
}