<?php


namespace App\Users\Mailers;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Document extends Mailable
{
    use Queueable, SerializesModels;

    public $username;

    public $document;

    public $status;

    public $emailConfiguration;

    public $title;

    public $subtitle;

    public $content;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($username, $document, $status, $emailConfiguration)
    {
        $this->username = $username;
        $this->document = $document;
        $this->status= $status;
        $this->title = _i('Verification of document');
        $this->subtitle = _i("¡Hello %s, you document %s has been %s!", [$this->username, $this->document, $this->status]);
        $this->content = _i('If you have any questions, please contact our support team');

        if (!is_null($emailConfiguration)) {
            $this->title = !is_null($emailConfiguration->title) ? $emailConfiguration->title : $this->title;
            $this->subtitle = !is_null($emailConfiguration->subtitle) ? $emailConfiguration->subtitle : $this->subtitle;
            $this->content = !is_null($emailConfiguration->content) ? $emailConfiguration->content : $this->content;
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = _i('Verifications of documents');
        return $this->subject($subject)
            ->view('back.users.emails.document-verification');
    }
}
