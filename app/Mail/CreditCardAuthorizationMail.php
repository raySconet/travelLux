<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Reservation;

class CreditCardAuthorizationMail extends Mailable
{
    public function __construct(
        public Reservation $reservation
    ) {}

    public function build()
    {
        return $this
            ->subject('Credit Card Authorization Form')
            ->view('emails.credit-card-email');
    }
}
