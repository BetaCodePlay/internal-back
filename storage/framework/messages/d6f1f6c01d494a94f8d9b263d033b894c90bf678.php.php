<?php

namespace App\Users\Mailers;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Activate extends Mailable
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

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($url, $username, $emailConfiguration)
    {
        $this->url = $url;
        $this->username = $username;
        $this->title = _i('Activate your account');
        $this->subtitle = _i("¡Hello %s, you are one step away from activating your account!", [$this->username]);
        $this->content = _i('Thank you for registering on our website. You must complete the activation process in order to enjoy our services. To proceed to activate the account, click on the following button. You have a period of 24 hours to activate your account after having registered otherwise you must perform the process again');
        $this->button = _i('Activate account');
        $this->footer = _i("If the button doesn't show correctly or doesn't work, copy and paste the following link into your browser:");

        if (!is_null($emailConfiguration)) {
            $this->title = !is_null($emailConfiguration->title) ? $emailConfiguration->title : $this->title;
            $this->subtitle = !is_null($emailConfiguration->subtitle) ? $emailConfiguration->subtitle : $this->subtitle;
            $this->content = !is_null($emailConfiguration->content) ? $emailConfiguration->content : $this->content;
            $this->button = !is_null($emailConfiguration->button) ? $emailConfiguration->button : $this->button;
            $this->footer = !is_null($emailConfiguration->footer) ? $emailConfiguration->footer : $this->footer;
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = _i('Activate your account');
        return $this->subject($subject)
            ->view('back.users.emails.activate');
    }
}
