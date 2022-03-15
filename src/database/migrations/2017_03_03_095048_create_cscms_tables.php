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

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCSCMSTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('cscms_audits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('enabled')->index()->default(1)->nullable();
            $table->integer('sort_order')->default(100)->index()->nullable();
            $table->bigInteger('user_id')->nullable()->index();
            $table->timestamps();
            $table->string('class')->index()->nullable();
            $table->string('name', 196)->nullable();
            $table->text('value')->nullable();
        });

        Schema::create('cscms_user_roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('enabled')->index()->default(1)->nullable();
            $table->integer('sort_order')->default(100)->index()->nullable();
            $table->timestamps();
            $table->string('name', 196)->nullable();
            $table->string('short_name', 196)->nullable();
        });

        Schema::create('cscms_capabilities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('enabled')->index()->default(1)->nullable();
            $table->integer('sort_order')->default(100)->index()->nullable();
            $table->timestamps();
            $table->string('name', 196);
            $table->string('nice_name', 196)->nullable();
            $table->string('group_name', 196)->nullable();
            $table->text('description')->nullable();
        });

        Schema::create('cscms_capabilities_user_roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_role_id')->index();
            $table->integer('capability_id')->index();
            $table->timestamps();
        });

        Schema::create('cscms_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('serialized')->default(0)->nullable();
            $table->string('name', 196)->index()->nullable();
            $table->string('nice_name', 196)->nullable();
            $table->string('group', 196)->nullable();
            $table->text('value')->nullable();
        });

        Schema::create('cscms_emails', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('enabled')->index()->default(1)->nullable();
            $table->bigInteger('user_id')->nullable()->index();
            $table->timestamps();
            $table->string('email')->unique();
        });

        Schema::create('cscms_email_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('enabled')->index()->default(1)->nullable();
            $table->integer('sort_order')->default(100)->index()->nullable();
            $table->timestamps();
            $table->string('name')->unique();
            $table->text('description')->nullable();
        });

        Schema::create('cscms_emails_email_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('email_id')->index();
            $table->integer('email_group_id')->index();
            $table->timestamps();
        });

        Schema::create('cscms_mail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('resend')->default(0);
            $table->boolean('enabled')->index()->default(1)->nullable();
            $table->boolean('viewed')->index()->nullable();
            $table->integer('mailshot_id')->index()->nullable();
            $table->bigInteger('user_id')->nullable()->index();
            $table->timestamps();
            $table->timestamp('sent_at')->nullable()->index();
            $table->string('to_email', 196)->index();
            $table->string('from_email', 196)->nullable();
            $table->string('sender', 196)->nullable();
            $table->string('subject', 255)->index()->nullable();
            $table->string('hash', 255)->index()->nullable();
            $table->text('body_html')->nullable();
            $table->text('body_text')->nullable();
            $table->text('files')->nullable();
        });

        Schema::create('cscms_users_user_roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->index();
            $table->integer('user_role_id')->index();
            $table->timestamps();
        });

        Schema::create('cscms_notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('enabled')->index()->default(1)->nullable();
            $table->integer('sort_order')->default(100)->index()->nullable();
            $table->bigInteger('user_id')->index();
            $table->timestamps();
            $table->timestamp('publish_at')->nullable();
            $table->string('subject', 196)->nullable();
            $table->text('message')->nullable();
        });

        Schema::create('cscms_notifications_read', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('read')->index()->default(0)->nullable();
            $table->bigInteger('user_id')->index();
            $table->integer('notification_id')->index()->nullable();
            $table->timestamps();
            $table->timestamp('seen_at')->nullable();
            $table->timestamp('read_at')->nullable();
        });

        Schema::create('cscms_languages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('enabled')->index()->default(0);
            $table->integer('sort_order')->default(100)->index()->nullable();
            $table->integer('image_id')->index()->nullable();
            $table->timestamps();
            $table->string('code', 5)->nullable();
            $table->string('name', 32)->nullable();
            $table->string('locale', 255)->nullable();
        });

        Schema::create('cscms_articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('enabled')->index()->default(0)->nullable();
            $table->bigInteger('parent_id')->index()->nullable();
            $table->bigInteger('user_id')->index();
            $table->integer('sort_order')->default(100)->index()->nullable();
            $table->integer('article_type_id')->index()->nullable();
            $table->timestamps();
            $table->timestamp('publish_at')->nullable()->nullable();
            $table->string('slug', 196)->nullable();
            $table->string('title', 255)->nullable();
            $table->string('meta_description', 255)->nullable();
        });

        Schema::create('cscms_articles_description', function (Blueprint $table) {
            $table->integer('article_id')->index();
            $table->integer('language_id')->index();
            $table->text('content')->nullable();
        });

        Schema::create('cscms_article_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('enabled')->index()->default(0)->nullable();
            $table->bigInteger('user_id')->index();
            $table->integer('sort_order')->default(100)->index()->nullable();
            $table->timestamps();
            $table->string('name', 196)->nullable();
            $table->string('slug', 196)->nullable();
            $table->text('description')->nullable();
        });

        Schema::create('cscms_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('enabled')->index()->default(0)->nullable();
            $table->integer('sort_order')->default(100)->index()->nullable();
            $table->bigInteger('user_id')->index();
            $table->float('filesize', 8, 2)->index()->default(0)->nullable();
            $table->timestamps();
            $table->string('mime', 32)->nullable();
            $table->string('name', 255)->nullable();
            $table->string('filename', 255)->nullable();
            $table->string('generated_filename', 255)->nullable();
        });

        Schema::create('cscms_uploads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('sort_order')->default(100)->index()->nullable();
            $table->bigInteger('user_id')->index();
            $table->float('filesize', 8, 2)->index()->default(0)->nullable();
            $table->timestamps();
            $table->string('mime', 32)->nullable();
            $table->string('name', 255)->nullable();
            $table->string('filename', 255)->nullable();
            $table->string('generated_filename', 255)->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('enabled')->default(0)->index()->after('id');
            $table->boolean('verified')->default(0)->index()->after('enabled');
            $table->integer('user_role_id')->index()->default(1)->after('verified');
            $table->string('username', 191)->unique()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('cscms_audits');
        Schema::dropIfExists('cscms_articles');
        Schema::dropIfExists('cscms_articles_description');
        Schema::dropIfExists('cscms_article_types');
        Schema::dropIfExists('cscms_capabilities');
        Schema::dropIfExists('cscms_capabilities_user_roles');
        Schema::dropIfExists('cscms_emails');
        Schema::dropIfExists('cscms_email_groups');
        Schema::dropIfExists('cscms_emails_email_groups');
        Schema::dropIfExists('cscms_images');
        Schema::dropIfExists('cscms_languages');
        Schema::dropIfExists('cscms_mail');
        Schema::dropIfExists('cscms_notifications');
        Schema::dropIfExists('cscms_notifications_read');
        Schema::dropIfExists('cscms_settings');
        Schema::dropIfExists('cscms_user_roles');
        Schema::dropIfExists('cscms_users_user_roles');
        Schema::dropIfExists('cscms_uploads');

        Schema::table('users', function ($table) {
            $table->dropColumn('enabled');
            $table->dropColumn('verified');
            $table->dropColumn('user_role_id');
            $table->dropColumn('username');
        });
    }
}
