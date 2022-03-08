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

namespace CoderStudios\CsCms\Listeners;

use CoderStudios\CsCms\Library\Mail;
use CoderStudios\CsCms\Library\Settings as SettingsLibrary;

class NewUserSubscriber
{
    public function __construct(Mail $mail, SettingsLibrary $settings)
    {
        $this->mail = $mail;
        $this->settings = $settings;
    }

    /**
     * Handle user login events.
     *
     * @param mixed $event
     */
    public function onUserCreate($event)
    {
        $config = $this->settings->getSettings();
        if ($config['user_verify_users']) {
            $token = substr(md5($event->user->email.'-'.date('Ymd')), 0, 8);
            $event->user->verified_token = $token;
            $event->user->save();
            $vars = [
                'token' => $token,
            ];
            $email = [
                'to_email' => $event->user->email,
                'from_email' => $config['mail_from_address'],
                'sender' => $config['mail_from_name'],
                'subject' => sprintf('Verify your account on %s', config('app.name')),
                'body_html' => view('cscms::frontend.default.emails.verify_account', compact('vars'))->render(),
                'body_text' => sprintf("Hi,\n\nPlease verify your account by following this link: %s \n\n\\Thanks", route('frontend.verify', ['token' => $vars['token']])),
            ];
            $this->mail->create($email);
        }
        $vars = [
            'name' => $event->user->name,
            'email' => $event->user->email,
            'ipaddress' => $_SERVER['REMOTE_ADDR'],
        ];
        $email = [
            'to_email' => $config['config_contact_email'],
            'from_email' => $config['mail_from_address'],
            'sender' => $config['mail_from_name'],
            'subject' => sprintf('New user account on %s', config('app.name')),
            'body_html' => view('cscms::backend.emails.new_user', compact('vars'))->render(),
            'body_text' => sprintf("Hi,\n\nThere is a new user, %s \n\nEmail: %s \n\nIP: %s\n\nThanks", $event->user->name, $event->user->email, $_SERVER['REMOTE_ADDR']),
        ];
        $this->mail->create($email);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Registered',
            'CoderStudios\Listeners\NewUserSubscriber@onUserCreate'
        );
    }
}
