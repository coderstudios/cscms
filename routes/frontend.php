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
Route::group(['namespace' => 'CoderStudios\CSCMS\Http\Controllers\Frontend', 'as' => 'frontend.', 'middleware' => ['cache', 'web']], function () {
    Route::get('/', ['as' => 'index', 'uses' => 'HomeController@index']);
    Route::get('/home', ['as' => 'home', 'middleware' => 'auth', 'uses' => 'HomeController@home']);
    Route::get('/image.png', ['as' => 'image', 'uses' => 'ImageController@render']);

    Route::get('/verify/{token}', ['as' => 'verify', 'uses' => 'UserController@verifyAccount']);

    Route::get('/profile', ['as' => 'profile', 'uses' => 'UserController@profile']);
    Route::post('/profile', ['as' => 'profile.update', 'uses' => 'UserController@updateProfile']);
    Route::get('/login', ['as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);
    Route::post('/login', ['as' => 'login', 'uses' => 'Auth\LoginController@login']);
    Route::post('/logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);
    Route::post('/password/email', ['as' => 'password.email', 'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail']);
    Route::post('/password/reset', ['as' => 'password.reset', 'uses' => 'Auth\ResetPasswordController@reset']);
    Route::get('/password/reset', ['as' => 'password.request', 'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm']);
    Route::get('/password/reset/{token}', ['as' => 'password.reset.form', 'uses' => 'Auth\ResetPasswordController@showResetForm']);
    Route::get('/register', ['as' => 'register', 'uses' => 'Auth\RegisterController@showRegistrationForm']);
    Route::post('/register', ['as' => 'register', 'uses' => 'Auth\RegisterController@register']);

    Route::get('{all}', ['as' => 'wildcard', 'uses' => 'HomeController@wildcard'])->where('all', '.*');
});
