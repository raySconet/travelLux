<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class DailyTasksReminderCommand extends Command
{
    protected $signature = 'reminder:tasks';

    protected $description = 'Send daily task reminder emails to agents';

    public function handle()
    {
        $today = Carbon::today();

        $users = User::where('isDeleted', 0)->where('is_disabled', '!=', 1)->get();

        foreach ($users as $user) {

            $tasks = $user->reservations()
                ->where('is_deleted', 0)
                ->with([
                    'customer',
                    'tasks' => function ($query) use ($today) {
                        $query->where('is_deleted', 0)
                              ->where('is_completed', 0)
                              ->whereDate('due_date', $today);
                    }
                ])
                ->get()
                ->flatMap(function ($reservation) {

                    return $reservation->tasks->map(function ($task) use ($reservation) {

                        return [
                            'task_name' => $task->task_name,
                            'priority' => ucfirst($task->priority),
                            'reservation_name' => $reservation->reservation_name,
                            'customer_name' => $reservation->customer ? $reservation->customer->lname . ', ' . $reservation->customer->fname . ' ' . $reservation->customer->mname : ''
                        ];

                    });

                });

            if ($tasks->isEmpty()) {
                continue;
            }

            if ($user->task_seperate_email_per_task) {

                foreach ($tasks as $task) {

                    Mail::send(
                        'emails.daily-task-single',
                        [
                            'task' => $task,
                            'today' => $today->format('l, F d, Y')
                        ],
                        function ($message) use ($user, $task) {

                            $message->to($user->email)->subject('Task Due - ' . $task['task_name']);

                        }
                    );

                    $this->info("Email sent to {$user->email}");

                }

            } else {

                Mail::send(
                    'emails.daily-task-summary',
                    [
                        'tasks' => $tasks,
                        'today' => $today->format('l, F d, Y')
                    ],
                    function ($message) use ($user) {

                        $message->to($user->email)->subject('Your Daily Tasks');
                    }
                );

                $this->info("Email sent to {$user->email}");

            }

        }

        $this->info('Finished.');
    }
}