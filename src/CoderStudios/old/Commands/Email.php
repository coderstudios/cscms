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

use CoderStudios\CsCms\Library\Mail as MailLibrary;
use CoderStudios\CsCms\Library\Settings;
use Config;
use Illuminate\Console\Command;
use Mail;

class Email extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send saved emails from the database';

    /**
     * Create a new command instance.
     */
    public function __construct(MailLibrary $mail, Settings $settings)
    {
        parent::__construct();
        $this->mail = $mail;
        $this->settings = $settings;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $mail_config = $this->settings->where('class', 'mail')->get();
        Config::set('mail.driver', $mail_config->where('name', 'mail_mail_driver')->pluck('value')->first());
        Config::set('mail.from.address', $mail_config->where('name', 'mail_from_address')->pluck('value')->first());
        Config::set('mail.from.name', $mail_config->where('name', 'mail_from_name')->pluck('value')->first());
        Config::set('services.mailgun.domain', $mail_config->where('name', 'mail_mailgun_domain')->pluck('value')->first());
        Config::set('services.mailgun.secret', $mail_config->where('name', 'mail_mailgun_secret')->pluck('value')->first());
        if ($mail_config->where('name', 'mail_mail_enabled')->pluck('value')->first()) {
            $emails = $this->mail
                ->where('enabled', 1)
                ->orWhere('resend', 1)
                ->whereNull('sent_at')
                ->take(100)
                ->get()
            ;

            if ($emails->count()) {
                foreach ($emails as $email) {
                    $result = Mail::raw('', function ($message) use ($email) {
                        $message->to($email->to_email, $email->sender)
                            ->subject($email->subject)
                            ->addPart($email->body_text)
                            ->setBody($email->body_html, 'text/html')
                        ;
                    });
                    $email->sent_at = date('Y-m-d H:i:s');
                    $email->enabled = 0;
                    $email->save();
                }
            }
        }
    }
}
