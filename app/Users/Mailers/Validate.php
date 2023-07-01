<?php

namespace App\Users\Mailers;

use Dotworkers\Configurations\Enums\EmailTypes;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class Users extends Mailable
{
    use Queueable, SerializesModels;

    public $url;

    public $username;

    public $emailConfiguration;

    public $emailType;

    public $ip;

    public $title;

    public $subtitle;

    public $content;

    public $button;

    public $footer;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($whitelabel, $url, $username, $emailConfiguration, $emailType, $ip)
    {
        $this->whitelabel = $whitelabel;
        $this->url = $url;
        $this->username = $username;
        $this->emailConfiguration = $emailConfiguration;
        $this->emailType = $emailType;
        $this->ip = $ip;
        switch ($emailType) {
            case EmailTypes::$validate_email:
            {
                $this->subject = _i('Activate your email');
                $this->title = _i('Activate your email');
                $this->subtitle = _i("¡Hello %s, you are one step away from activating your email!", [$this->username]);
                $this->content = _i('Thank you for registering on our website. You must complete the activation process in order to enjoy our services. To proceed to activate the email, click on the following button. You have a period of 24 hours to activate your email after having registered otherwise you must perform the process again');
                $this->button = _i('Activate email');
                $this->footer = _i("If the button doesn't show correctly or doesn't work, copy and paste the following link into your browser:");

                if (!is_null($emailConfiguration)) {
                    $this->subject = !is_null($emailConfiguration->subject) ? $emailConfiguration->subject : $this->subject;
                    $this->title = !is_null($emailConfiguration->title) ? $emailConfiguration->title : $this->title;
                    $this->subtitle = !is_null($emailConfiguration->subtitle) ? $emailConfiguration->subtitle : $this->subtitle;
                    $this->content = !is_null($emailConfiguration->content) ? $emailConfiguration->content : $this->content;
                    $this->button = !is_null($emailConfiguration->button) ? $emailConfiguration->button : $this->button;
                    $this->footer = !is_null($emailConfiguration->footer) ? $emailConfiguration->footer : $this->footer;
                }
                break;
            }
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)
        ->view('back.users.emails.validate');
    }
}