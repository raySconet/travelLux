<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Mpdf\Mpdf;

class ReservationDetailsMail extends Mailable
{
    use Queueable, SerializesModels;

    public Reservation $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function build()
    {
        $mpdf = new Mpdf();

        $html = view(
            'reservations.partials.trip-itinerary',
            [
                'reservation' => $this->reservation
            ]
        )->render();

        $mpdf->WriteHTML($html);

        $pdf = $mpdf->Output('', 'S');

        return $this
            ->subject('Trip Itinerary')
            ->view('emails.reservation-details')
            ->attachData(
                $pdf,
                'Trip Itinerary.pdf',
                [
                    'mime' => 'application/pdf'
                ]
            );
    }
}