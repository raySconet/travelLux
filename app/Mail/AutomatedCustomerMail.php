<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class AutomatedCustomerMail extends Mailable
{
    public function __construct(
        public string $subjectLine,
        public string $messageBody,
        public string $agentName,
        public ?string $agentPhone,
        public string $agentEmail
    ) {
    }

    public function build()
    {
        return $this
            ->subject($this->subjectLine)
            ->view('emails.automatedCustomer');
    }
}