<?php

namespace App\Users\Mailers;

use Dotworkers\Configurations\Enums\EmailTypes;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Users extends Mailable
{
    use Queueable, SerializesModels;

    public $url;

    public $username;

    public $emailConfiguration;

    public $title;

    public $subtitle;

    public $content;

    public $button;

    public $footer;

    public $emailType;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($url, $username, $emailConfiguration, $emailType)
    {
        $this->url = $url;
        $this->username = $username;
        $this->emailType = $emailType;
        switch ($emailType) {
            case EmailTypes::$login_notification:
            {
                $this->title = _i('Welcome %s', $username);
                $this->subject = _i('Welcome %s', $username);
                $this->subtitle = _i('Your login was successful');
                $this->content = _i('We notify you that your login was successful. If you are unaware of this activity please let us know');
                $this->button = _i('Notify');
                $this->footer = _i("If the button doesn't show correctly or doesn't work, copy and paste the following link into your browser:");

                /*if (!is_null($emailConfiguration)) {
                    $this->title = !is_null($emailConfiguration->title) ? $emailConfiguration->title : $this->title;
                    $this->subtitle = !is_null($emailConfiguration->subtitle) ? $emailConfiguration->subtitle : $this->subtitle;
                    $this->content = !is_null($emailConfiguration->content) ? $emailConfiguration->content : $this->content;
                    $this->button = !is_null($emailConfiguration->button) ? $emailConfiguration->button : $this->button;
                    $this->footer = !is_null($emailConfiguration->footer) ? $emailConfiguration->footer : $this->footer;
                }*/
                break;
            }
            case EmailTypes::$activate_account:
            {
                $this->subject = _i('Activate your account');
                $this->title = _i('Activate your account');
                $this->subtitle = _i("¡Hello %s, you are one step away from activating your account!", [$this->username]);
                $this->content = _i('Thank you for registering on our website. You must complete the activation process in order to enjoy our services. To proceed to activate the account, click on the following button. You have a period of 24 hours to activate your account after having registered otherwise you must perform the process again');
                $this->button = _i('Activate account');
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
            case EmailTypes::$password_reset:
            {
                $this->subject = _i('Reset your password');
                $this->title = _i('Reset your password');
                $this->subtitle = _i("¡Hello  %s, you have requested the reset of your password!", [$this->username]);
                $this->content = _i('To configure a new password please click on the following button. You have a period of 24 hours to activate your account after having registered otherwise you must perform the process again');
                $this->button = _i('Reset password');
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
        ->view('back.users.emails.activate');
    }
}
