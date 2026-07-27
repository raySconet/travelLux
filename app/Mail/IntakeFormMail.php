<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class IntakeFormMail extends Mailable
{
    use SerializesModels;

    public $customerName;
    public $userName;
    public $token;

    public function __construct($customerName, $userName, $token)
    {
        $this->customerName = $customerName;
        $this->userName = $userName;
        $this->token = $token;
    }

    public function build()
    {
        return $this->subject('Travelux - Trip Inquiry Form')->view('emails.intakeFormEmail');
    }
}