<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class RegistrationInvitationMail extends Mailable
{
    public $customerName;
    public $userName;
    public $invitation;

    public function __construct($customerName, $userName, $invitation)
    {
        $this->customerName = $customerName;
        $this->userName = $userName;
        $this->invitation = $invitation;
    }

    public function build()
    {
        return $this
            ->subject('Registration Invitation From Travelux')
            ->view('emails.registrationInvitation');
    }
}