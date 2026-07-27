<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Illuminate\Support\Facades\Mail;

class DisneyWorldReminderCommand extends Command
{
    protected $signature = 'reminder:disney';

    protected $description = 'Send Disney World transfer reminders';

    public function handle()
    {
        $dateToCheck = now()->addDays(30)->toDateString();

        $reservations = Reservation::with(['customer', 'agent'])
            ->where('is_deleted', 0)
            ->where('destination_id', 1)
            ->whereDate('checkin_date', $dateToCheck)
            ->get()
        ;

        foreach ($reservations as $reservation) {

            Mail::raw(
                "Please double check to make sure you set up transfers to the resort for your reservation.\n\nReservation #: {$reservation->reservation_number}\nClient: {$reservation->customer->fname} {$reservation->customer->lname}",
                function ($message) use ($reservation) {
                    $message->to($reservation->agent->email)->subject('Disney World Transfer Reminder');
                }
            );

            $this->info("Email sent to {$reservation->agent->email}");
        }

        $this->info('Finished.');
    }
}