<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Illuminate\Support\Facades\Mail;

class ReservationReminderCommand extends Command
{
    protected $signature = 'reminder:reservation';

    protected $description = 'Send reminder emails for reservations that returned one week ago';

    public function handle()
    {
        $dateToCheck = now()->subDays(7)->toDateString();

        $reservations = Reservation::with(['customer','agent','product'])
            ->where('is_deleted', 0)
            ->whereDate('checkout_date', $dateToCheck)
            ->whereNotIn('status', ['Prospect','Canceled','On Hold','Canceled - Commission Protected'])
            ->get();

        foreach ($reservations as $reservation) {

            if (!$reservation->agent || empty($reservation->agent->email)) {
                continue;
            }

            $customerName = '';

            if ($reservation->customer) {
                $customerName = $reservation->customer->fname . ' ' . $reservation->customer->lname;
            }

            $productName = $reservation->product ? $reservation->product->product_name : '';

            Mail::raw(
                "Reminder: Your client has been back a week.\n\n" .
                "Client: {$customerName}\n" .
                "Reservation Number: {$reservation->reservation_number}\n" .
                "Supplier Name: {$productName}",
                function ($message) use ($reservation, $customerName) {

                    $message->to($reservation->agent->email)->subject("Your client {$customerName} has been back a week");
                }
            );

            $this->info("Email sent to {$reservation->agent->email}");
        }

        $this->info('Finished.');
    }
}