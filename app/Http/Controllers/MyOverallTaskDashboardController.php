<?php

namespace App\Http\Controllers;

use App\Models\ReservationTask;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MyOverallTaskDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();
        $twoWeeks = Carbon::today()->addDays(14)->toDateString();
        $thirtyDays = Carbon::today()->addDays(30)->toDateString();

        $taskStats = ReservationTask::query()
            ->where('is_completed', 0)
            ->where('is_deleted', 0)
            ->where('created_by', auth()->id())
            ->whereHas('reservation', function ($q) {
                $q->where('is_deleted', 0);
            })
            ->selectRaw("
                priority,

                SUM(CASE
                    WHEN due_date < ?
                    THEN 1 ELSE 0
                END) as past_due,

                SUM(CASE
                    WHEN DATE(due_date) = ?
                    THEN 1 ELSE 0
                END) as due_today,

                SUM(CASE
                    WHEN due_date > ?
                    AND due_date <= ?
                    THEN 1 ELSE 0
                END) as two_weeks,

                SUM(CASE
                    WHEN due_date > ?
                    AND due_date <= ?
                    THEN 1 ELSE 0
                END) as thirty_days
            ", [
                $today,
                $today,

                // Two Weeks
                $today,
                $twoWeeks,

                // Thirty Days
                $today,
                $thirtyDays
            ])
            ->groupBy('priority')
            ->get()
            ->keyBy('priority');

        $highPriority = $taskStats->get('High');
        $mediumPriority = $taskStats->get('Medium');
        $lowPriority = $taskStats->get('Low');

        $allTasks = (object) [
            'past_due' => ($highPriority->past_due ?? 0) + ($mediumPriority->past_due ?? 0) + ($lowPriority->past_due ?? 0),
            'due_today' => ($highPriority->due_today ?? 0) + ($mediumPriority->due_today ?? 0) + ($lowPriority->due_today ?? 0),
            'two_weeks' => ($highPriority->two_weeks ?? 0) + ($mediumPriority->two_weeks ?? 0) + ($lowPriority->two_weeks ?? 0),
            'thirty_days' => ($highPriority->thirty_days ?? 0) + ($mediumPriority->thirty_days ?? 0) + ($lowPriority->thirty_days ?? 0),
        ];

        return view('dashboards.myOverallTaskDashboard', compact('highPriority','mediumPriority', 'lowPriority', 'allTasks'));
    }

    public function tasks($priority, $period)
    {
        $today = Carbon::today();
        $twoWeeks = Carbon::today()->addDays(14);
        $thirtyDays = Carbon::today()->addDays(30);

        $query = ReservationTask::query()
                    ->with([
                        'reservation:id,customer_id',
                        'reservation.customer:id,fname,lname'
                    ])
                    ->where('is_completed', 0)
                    ->where('is_deleted', 0)
                    ->where('created_by', auth()->id())
                    ->whereHas('reservation', function ($q) {
                        $q->where('is_deleted', 0);
                    });
                    
        if ($priority !== 'All') {
            $query->where('priority', $priority);
        }

        switch ($period) {

            case 'past_due':
                $query->whereDate('due_date', '<', $today);
                break;

            case 'due_today':
                $query->whereDate('due_date', $today);
                break;

            case 'two_weeks':
                $query->whereDate('due_date', '>', $today)->whereDate('due_date', '<=', $twoWeeks);
                break;

            case 'thirty_days':
                $query->whereDate('due_date', '>', $today)->whereDate('due_date', '<=', $thirtyDays);
                break;
        }

        $tasks = $query->select(['id','reservation_id','task_name','due_date','priority'])->orderBy('due_date')->get();

        return response()->json($tasks);
    }

    public function completeOnlyTask(ReservationTask $task)
    {
        if (!$task->is_completed) {
            $task->update([
                'is_completed' => 1,
                'is_completed_by' => auth()->id(),
                'is_completed_on' => now(),
                'last_modified_by' => auth()->id(),
                'last_modified_on' => now(),
            ]);
        }

        return back()->with('success', 'Task completed');
    }

    public function destroy(ReservationTask $task)
    {
        $task->update([
            'is_deleted' => 1,
            'last_modified_by' => auth()->id(),
            'last_modified_on' => now(),
        ]);

        return back()->with('success', 'Task deleted');
    }

}