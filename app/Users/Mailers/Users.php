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
        Log::debug(__METHOD__, ['whitelabel' => $whitelabel, 'url' => $url, 'email' => $emailConfiguration, 'emailType' => $emailType, 'ip' => $ip]);
        $this->whitelabel = $whitelabel;
        $this->url = $url;
        $this->username = $username;
        $this->emailConfiguration = $emailConfiguration;
        $this->emailType = $emailType;
        $this->ip = $ip;
        switch ($emailType) {
            case EmailTypes::$login_notification:
            {
                $this->subject = _i('Login Notification');
                $this->title = _i('Welcome %s', $username);
                $this->subtitle = _i('Your login was successful');
                $this->content = _i('We inform you that you are logging from this IP address %s', [$this->ip]);
                $this->footer = _i("If you have no idea about this activity please contact support or your top agent");

                if (!is_null($emailConfiguration)) {
                    $this->title = !is_null($emailConfiguration->title) ? $emailConfiguration->title : $this->title;
                    $this->subtitle = !is_null($emailConfiguration->subtitle) ? $emailConfiguration->subtitle : $this->subtitle;
                    $this->content = !is_null($emailConfiguration->content) ? $emailConfiguration->content : $this->content;
                    $this->footer = !is_null($emailConfiguration->footer) ? $emailConfiguration->footer : $this->footer;
                }
                break;
            }
            case EmailTypes::$password_change_notification:
            {
                $this->subject = _i('Password change notification');
                $this->title = _i('Welcome %s', $username);
                $this->subtitle = _i("¡Hello %s, you are informed that your password has been changed", [$this->username]);
                $this->content = _i('This message is to notify you that your password has been changed.');
                $this->footer = _i("If the button doesn't show correctly or doesn't work, copy and paste the following link into your browser:");

                if (!is_null($emailConfiguration)) {
                    $this->subject = !is_null($emailConfiguration->subject) ? $emailConfiguration->subject : $this->subject;
                    $this->title = !is_null($emailConfiguration->title) ? $emailConfiguration->title : $this->title;
                    $this->subtitle = !is_null($emailConfiguration->subtitle) ? $emailConfiguration->subtitle : $this->subtitle;
                    $this->content = !is_null($emailConfiguration->content) ? $emailConfiguration->content : $this->content;
                    $this->footer = !is_null($emailConfiguration->footer) ? $emailConfiguration->footer : $this->footer;
                }

                break;
            }
            case EmailTypes::$invalid_password_notification:
            {
                $this->subject = _i('Invalid password notification');
                $this->title = _i('Invalid password');
                $this->subtitle = _i("¡Hello  %s, you have entered an invalid password!", [$this->username]);
                $this->content = _i('This message is to notify that the password entered is incorrect');
                $this->footer = _i("If the button doesn't show correctly or doesn't work, copy and paste the following link into your browser:");

                if (!is_null($emailConfiguration)) {
                    $this->subject = !is_null($emailConfiguration->subject) ? $emailConfiguration->subject : $this->subject;
                    $this->title = !is_null($emailConfiguration->title) ? $emailConfiguration->title : $this->title;
                    $this->subtitle = !is_null($emailConfiguration->subtitle) ? $emailConfiguration->subtitle : $this->subtitle;
                    $this->content = !is_null($emailConfiguration->content) ? $emailConfiguration->content : $this->content;
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
        ->view('back.users.emails.users');
    }
}
