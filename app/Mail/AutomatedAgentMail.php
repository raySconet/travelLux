<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class AutomatedAgentMail extends Mailable
{
    public function __construct(
        public string $subjectLine,
        public string $messageBody
    ) {
    }

    public function build()
    {
        return $this
            ->subject($this->subjectLine)
            ->view('emails.automatedAgent');
    }
}