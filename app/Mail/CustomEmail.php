<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $subjectText;
    public $bodyMessage;

    public function __construct($subject, $message)
    {
        $this->subjectText = $subject;
        $this->bodyMessage = $message;
    }

    public function build()
    {
        return $this->subject($this->subjectText)
                    ->html('<p>' . nl2br(e($this->bodyMessage)) . '</p>');
    }
}