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

Route::group( [ 'namespace' => 'CoderStudios\CSCMS\Http\Controllers\Backend', 'prefix' => 'admin', 'as' => 'backend.', 'middleware' => ['auth'] ] , function() {
	Route::get('/image.png', ['as' => 'image', 'uses' => 'ImageController@render']);
});

Route::group( [ 'namespace' => 'CoderStudios\CSCMS\Http\Controllers\Backend', 'prefix' => 'admin', 'as' => 'backend.', 'middleware' => ['web','auth','cache']] , function() {

	Route::get('/', ['as' => 'index', 'uses' => 'HomeController@index']);
	Route::get('/phpinfo', ['as' => 'phpinfo', 'uses' => 'HomeController@phpinfo']);
	Route::get('/access-denied', ['as' => 'access_denied', 'uses' => 'HomeController@accessDenied']);

	Route::get('/log', ['as' => 'log', 'uses' => 'LogController@index']);
	Route::post('/clear-log', ['as' => 'clear-log', 'uses' => 'LogController@delete']);

	Route::get('/emails', ['as' => 'emails', 'uses' => 'EmailController@index']);
	Route::get('/emails/send', ['as' => 'emails.send', 'uses' => 'EmailController@send']);
	Route::get('/emails/create', ['as' => 'emails.create', 'uses' => 'EmailController@create']);
	Route::post('/emails/store', ['as' => 'emails.store', 'uses' => 'EmailController@store']);
	Route::get('/emails/{id}/edit', ['as' => 'emails.email.edit', 'uses' => 'EmailController@edit']);
	Route::post('/emails/{id}/edit', ['as' => 'emails.email.update', 'uses' => 'EmailController@update']);

	Route::get('/article_types', ['as' => 'article_types', 'uses' => 'ArticleTypeController@index']);
	Route::get('/article_types/create', ['as' => 'article_types.article_type.create', 'uses' => 'ArticleTypeController@create']);
	Route::post('/article_types/store', ['as' => 'article_types.article_type.store', 'uses' => 'ArticleTypeController@store']);
	Route::get('/article_types/{id}/edit', ['as' => 'article_types.article_type.edit', 'uses' => 'ArticleTypeController@edit']);
	Route::post('/article_types/{id}/edit', ['as' => 'article_types.article_type.update', 'uses' => 'ArticleTypeController@update']);
	Route::post('/article_types/{id}/delete', ['as' => 'article_types.article_type.delete', 'uses' => 'ArticleTypeController@delete']);

	Route::get('/articles', ['as' => 'articles', 'uses' => 'ArticleController@index']);
	Route::get('/articles/create', ['as' => 'articles.article.create', 'uses' => 'ArticleController@create']);
	Route::post('/articles/store', ['as' => 'articles.article.store', 'uses' => 'ArticleController@store']);
	Route::get('/articles/{id}/edit', ['as' => 'articles.article.edit', 'uses' => 'ArticleController@edit']);
	Route::post('/articles/{id}/delete', ['as' => 'articles.article.delete', 'uses' => 'ArticleController@delete']);

	Route::get('/images', ['as' => 'images', 'uses' => 'ImageController@index']);
	Route::get('/images/create', ['as' => 'images.image.create', 'uses' => 'ImageController@create']);
	Route::post('/images/store', ['as' => 'images.image.store', 'uses' => 'ImageController@store']);
	Route::get('/images/{id}/edit', ['as' => 'images.image.edit', 'uses' => 'ImageController@edit']);
	Route::post('/images/{id}/delete', ['as' => 'images.image.delete', 'uses' => 'ImageController@delete']);

	Route::get('/uploads', ['as' => 'uploads', 'uses' => 'UploadController@index']);
	Route::get('/uploads/create', ['as' => 'uploads.upload.create', 'uses' => 'UploadController@create']);
	Route::post('/uploads/store', ['as' => 'uploads.upload.store', 'uses' => 'UploadController@store']);
	Route::get('/uploads/{id}/edit', ['as' => 'uploads.upload.edit', 'uses' => 'UploadController@edit']);
	Route::post('/uploads/{id}/delete', ['as' => 'uploads.upload.delete', 'uses' => 'UploadController@delete']);

	Route::get('/notifications', ['as' => 'notifications', 'uses' => 'NotificationController@index']);
	Route::get('/notifications/create', ['as' => 'notifications.create', 'uses' => 'NotificationController@create']);
	Route::post('/notifications/store', ['as' => 'notifications.store', 'uses' => 'NotificationController@store']);
	Route::get('/notifications/{id}/edit', ['as' => 'notifications.notification.edit', 'uses' => 'NotificationController@edit']);
	Route::post('/notifications/{id}/edit', ['as' => 'notifications.notification.update', 'uses' => 'NotificationController@update']);
	Route::post('/notifications/{id}/delete', ['as' => 'notifications.notification.delete', 'uses' => 'NotificationController@delete']);

	Route::get('/email_groups', ['as' => 'email_groups', 'uses' => 'EmailGroupsController@index']);
	Route::get('/email_groups/create', ['as' => 'email_groups.create', 'uses' => 'EmailGroupsController@create']);
	Route::post('/email_groups/store', ['as' => 'email_groups.store', 'uses' => 'EmailGroupsController@store']);
	Route::get('/email_groups/{id}/edit', ['as' => 'email_groups.email_group.edit', 'uses' => 'EmailGroupsController@edit']);
	Route::post('/email_groups/{id}/edit', ['as' => 'email_groups.email_group.update', 'uses' => 'EmailGroupsController@update']);
	Route::post('/email_groups/{id}/delete', ['as' => 'email_groups.email_group.delete', 'uses' => 'EmailGroupsController@delete']);

	Route::get('/export', ['as' => 'export', 'uses' => 'ExportController@index']);
	Route::get('/export/capabilities', ['as' => 'export.capabilities', 'uses' => 'ExportController@capabilities']);
	Route::get('/export/settings', ['as' => 'export.settings', 'uses' => 'ExportController@settings']);
	Route::get('/export/users', ['as' => 'export.users', 'uses' => 'ExportController@users']);
	Route::get('/export/user_roles', ['as' => 'export.user_roles', 'uses' => 'ExportController@userRoles']);
	Route::get('/export/emails', ['as' => 'export.email', 'uses' => 'ExportController@emails']);

	Route::get('/import', ['as' => 'import', 'uses' => 'ImportController@index']);
	Route::post('/import', ['as' => 'import.process', 'uses' => 'ImportController@import']);

	Route::get('/languages', ['as' => 'languages', 'middleware' => 'can:view,CoderStudios\CSCMS\Models\Language', 'uses' => 'LanguageController@index']);
	Route::get('/languages/create', ['as' => 'languages.language.create', 'middleware' => 'can:create,CoderStudios\CSCMS\Models\Language', 'uses' => 'LanguageController@create']);
	Route::post('/languages/store', ['as' => 'languages.language.store', 'middleware' => 'can:create,CoderStudios\CSCMS\Models\Language', 'uses' => 'LanguageController@store']);
	Route::get('/languages/{id}/edit', ['as' => 'languages.language.edit', 'middleware' => 'can:edit,CoderStudios\CSCMS\Models\Language', 'uses' => 'LanguageController@edit']);
	Route::post('/languages/{id}/edit', ['as' => 'languages.language.update', 'middleware' => 'can:update,CoderStudios\CSCMS\Models\Language', 'uses' => 'LanguageController@update']);
	Route::post('/languages/{id}/delete', ['as' => 'languages.language.delete', 'middleware' => 'can:delete,CoderStudios\CSCMS\Models\Language', 'uses' => 'LanguageController@delete']);

	Route::get('/settings', ['as' => 'settings', 'middleware' => 'can:view,CoderStudios\CSCMS\Models\Setting', 'uses' => 'SettingsController@index']);
	Route::get('/settings/create', ['as' => 'settings.setting.create', 'middleware' => 'can:create,CoderStudios\CSCMS\Models\Setting', 'uses' => 'SettingsController@create']);
	Route::post('/settings/store', ['as' => 'settings.setting.store', 'middleware' => 'can:create,CoderStudios\CSCMS\Models\Setting', 'uses' => 'SettingsController@store']);
	Route::get('/settings/{id}/edit', ['as' => 'settings.setting.edit', 'middleware' => 'can:edit,CoderStudios\CSCMS\Models\Setting', 'uses' => 'SettingsController@edit']);
	Route::post('/settings/{id}/edit', ['as' => 'settings.setting.update', 'middleware' => 'can:update,CoderStudios\CSCMS\Models\Setting', 'uses' => 'SettingsController@update']);
	Route::post('/settings/{id}/delete', ['as' => 'settings.setting.delete', 'middleware' => 'can:delete,CoderStudios\CSCMS\Models\Setting', 'uses' => 'SettingsController@delete']);

	Route::get('/backups', ['as' => 'backups', 'uses' => 'BackupsController@index']);
	Route::post('/backups/backup', ['as' => 'backups.backup', 'uses' => 'BackupsController@backup']);
	Route::get('/backups/backup/delete', ['as' => 'backups.backup.delete', 'uses' => 'BackupsController@delete']);

	Route::get('/download', ['as' => 'download', 'uses' => 'DownloadController@index']);

	Route::get('/user_roles', ['as' => 'user_roles', 'middleware' => 'can:view,CoderStudios\CSCMS\Models\UserTypes', 'uses' => 'UserRolesController@index']);
	Route::get('/user_roles/create', ['as' => 'user_roles.create', 'middleware' => 'can:create,CoderStudios\CSCMS\Models\UserTypes', 'uses' => 'UserRolesController@create']);
	Route::post('/user_roles/store', ['as' => 'user_roles.store', 'middleware' => 'can:create,CoderStudios\CSCMS\Models\UserTypes', 'uses' => 'UserRolesController@store']);
	Route::get('/user_roles/{id}', ['as' => 'user_roles.user_role', 'middleware' => 'can:view,CoderStudios\CSCMS\Models\UserTypes', 'uses' => 'UserRolesController@view']);
	Route::get('/user_roles/{id}/edit', ['as' => 'user_roles.user_role.edit', 'middleware' => 'can:edit,CoderStudios\CSCMS\Models\UserTypes','uses' => 'UserRolesController@edit']);
	Route::post('/user_roles/{id}/edit', ['as' => 'user_roles.user_role.update', 'middleware' => 'can:update,CoderStudios\CSCMS\Models\UserTypes', 'uses' => 'UserRolesController@update']);
	Route::post('/user_roles/{id}/delete', ['as' => 'user_roles.user_role.delete', 'middleware' => 'can:delete,CoderStudios\CSCMS\Models\UserTypes', 'uses' => 'UserRolesController@delete']);

	Route::get('/capabilities', ['as' => 'capabilities', 'middleware' => 'can:view,CoderStudios\CSCMS\Models\Capability', 'uses' => 'CapabilityController@index']);
	Route::get('/capabilities/create', ['as' => 'capabilities.create', 'middleware' => 'can:create,CoderStudios\CSCMS\Models\Capability', 'uses' => 'CapabilityController@create']);
	Route::post('/capabilities/store', ['as' => 'capabilities.store', 'middleware' => 'can:create,CoderStudios\CSCMS\Models\Capability', 'uses' => 'CapabilityController@store']);
	Route::get('/capabilities/{id}', ['as' => 'capabilities.capability', 'middleware' => 'can:view,CoderStudios\CSCMS\Models\Capability', 'uses' => 'CapabilityController@view']);
	Route::get('/capabilities/{id}/edit', ['as' => 'capabilities.capability.edit', 'middleware' => 'can:edit,CoderStudios\CSCMS\Models\Capability', 'uses' => 'CapabilityController@edit']);
	Route::post('/capabilities/{id}/edit', ['as' => 'capabilities.capability.update', 'middleware' => 'can:update,CoderStudios\CSCMS\Models\Capability', 'uses' => 'CapabilityController@update']);
	Route::post('/capabilities/{id}/delete', ['as' => 'capabilities.capability.delete', 'middleware' => 'can:delete,CoderStudios\CSCMS\Models\Capability', 'uses' => 'CapabilityController@delete']);

	Route::get('/users', ['as' => 'users', 'middleware' => 'can:view,CoderStudios\CSCMS\Models\User', 'uses' => 'UserController@index']);
	Route::get('/users/create', ['as' => 'users.create', 'middleware' => 'can:create,CoderStudios\CSCMS\Models\User', 'uses' => 'UserController@create']);
	Route::post('/users/store', ['as' => 'users.store', 'middleware' => 'can:create,CoderStudios\CSCMS\Models\User', 'uses' => 'UserController@store']);
	Route::get('/users/{username}', ['as' => 'users.user', 'middleware' => 'can:view,CoderStudios\CSCMS\Models\User', 'uses' => 'UserController@view']);
	Route::get('/users/{username}/resendVerifyEmail', ['as' => 'users.user.resendverify', 'middleware' => 'can:update,CoderStudios\CSCMS\Models\User', 'uses' => 'UserController@resendVerify']);
	Route::get('/users/{username}/edit', ['as' => 'users.user.edit', 'middleware' => 'can:edit,CoderStudios\CSCMS\Models\User', 'uses' => 'UserController@edit']);
	Route::post('/users/{username}/edit', ['as' => 'users.user.update', 'middleware' => 'can:update,CoderStudios\CSCMS\Models\User', 'uses' => 'UserController@update']);
	Route::post('/users/{username}/delete', ['as' => 'users.user.delete', 'middleware' => 'can:delete,CoderStudios\CSCMS\Models\User', 'uses' => 'UserController@delete']);

	Route::get('/cache', ['as' => 'cache', 'uses' => 'CacheController@index']);
	Route::post('/cache/frontend/clear', ['as' => 'cache.frontend.clear', 'uses' => 'CacheController@clearFrontend']);
	Route::post('/cache/backend/clear', ['as' => 'cache.backend.clear', 'uses' => 'CacheController@clearBackend']);
	Route::post('/cache/data/clear', ['as' => 'cache.data.clear', 'uses' => 'CacheController@clearData']);
	Route::post('/cache/images/clear', ['as' => 'cache.images.clear', 'uses' => 'CacheController@clearImage']);
	Route::post('/cache/all/clear', ['as' => 'cache.all.clear', 'uses' => 'CacheController@clear']);
	Route::post('/cache/optimise/urls', ['as' => 'cache.optimise.url', 'uses' => 'CacheController@optimiseUrls']);
	Route::post('/cache/optimise/config', ['as' => 'cache.optimise.config', 'uses' => 'CacheController@optimiseConfig']);
	Route::post('/cache/optimise/classes', ['as' => 'cache.optimise.classes', 'uses' => 'CacheController@optimiseClasses']);

});

Route::group( [ 'namespace' => 'CoderStudios\CSCMS\Http\Controllers\Backend', 'prefix' => 'admin', 'as' => 'backend.', 'middleware' => ['web']] , function() {

	Route::get('/login', ['as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);
	Route::post('/login', ['as' => 'login', 'uses' => 'Auth\LoginController@login']);
	Route::post('/logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);
	Route::post('/password/email', ['as' => 'password.email', 'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail']);
	Route::post('/password/reset', ['as' => 'password.reset', 'uses' => 'Auth\ResetPasswordController@reset']);
	Route::get('/password/reset', ['as' => 'password.request', 'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm']);
	Route::get('/password/reset/{token}', ['as' => 'password.reset.form', 'uses' => 'Auth\ResetPasswordController@showResetForm']);
	Route::get('/register', ['as' => 'register', 'uses' => 'Auth\RegisterController@showRegistrationForm']);
	Route::post('/register', ['as' => 'register', 'uses' => 'Auth\RegisterController@register']);

});