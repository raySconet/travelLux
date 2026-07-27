<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ThisWeekReservationsReminderCommand extends Command
{
    protected $signature = 'reminder:this-week';

    protected $description = 'Send reminders to agents about clients traveling this week';

    public function handle()
    {
        $startDate = Carbon::now()->startOfWeek(Carbon::WEDNESDAY);
        $endDate = $startDate->copy()->addDays(7);

        $reservations = Reservation::with(['agent','customer','product',])
            ->where('is_deleted', 0)
            ->whereBetween('checkin_date', [
                $startDate->toDateString(),
                $endDate->toDateString()
            ])
            ->whereNotIn('status', ['Canceled','Canceled - Commission Protected',])
            ->get()
        ;

        $reservationsByAgent = $reservations->groupBy('agent_id');

        foreach ($reservationsByAgent as $agentReservations) {

            $agent = $agentReservations->first()->agent;

            if (!$agent || empty($agent->email)) {
                continue;
            }

            $message = '';

            foreach ($agentReservations as $reservation) {

                $message .=
                    "Client: {$reservation->customer->fname} {$reservation->customer->lname}\n" .
                    "Reservation #: {$reservation->reservation_number}\n" .
                    "Supplier: {$reservation->product->product_name}\n" .
                    "Departure Date: " . Carbon::parse($reservation->checkin_date)->format('m/d/Y') .
                    "\n\n";
            }

            Mail::raw($message, function ($mail) use ($agent) {
                $mail->to($agent->email)->subject('Clients Traveling Reminder');
            });

            $this->info("Email sent to {$agent->email}");
        }

        $this->info('Finished.');
    }
}