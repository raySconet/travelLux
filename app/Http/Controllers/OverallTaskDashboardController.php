<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ReservationTask;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OverallTaskDashboardController extends Controller
{
    private function checkAdmin()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }
    }

    public function index()
    {
        $this->checkAdmin();
        $users = User::select('id','fname','lname')->where('isDeleted',0)->get();
        $stats = $this->getStats();

        return view('dashboards.overallTaskDashboard',array_merge(compact('users'),$stats));
    }

    public function stats($agent = null)
    {
        return response()->json(
            $this->getStats(
                $agent != -1 ? $agent : null
            )
        );
    }

    private function getStats($agentId = null)
    {
        $today = Carbon::today()->toDateString();
        $twoWeeks = Carbon::today()->addDays(14)->toDateString();
        $thirtyDays = Carbon::today()->addDays(30)->toDateString();

        $query = ReservationTask::query()
            ->where('is_completed',0)
            ->where('is_deleted',0)
            ->whereHas('reservation',function($q){
                $q->where('is_deleted',0);
            });

        if ($agentId) {
            $query->where('created_by',$agentId);
        }

        $taskStats = $query
            ->selectRaw("
                priority,

                SUM(
                    CASE
                        WHEN due_date < ?
                        THEN 1
                        ELSE 0
                    END
                ) as past_due,

                SUM(
                    CASE
                        WHEN DATE(due_date) = ?
                        THEN 1
                        ELSE 0
                    END
                ) as due_today,

                SUM(
                    CASE
                        WHEN due_date > ?
                        AND due_date <= ?
                        THEN 1
                        ELSE 0
                    END
                ) as two_weeks,

                SUM(
                    CASE
                        WHEN due_date > ?
                        AND due_date <= ?
                        THEN 1
                        ELSE 0
                    END
                ) as thirty_days
            ",[
                $today,
                $today,

                $today,
                $twoWeeks,

                $today,
                $thirtyDays
            ])
            ->groupBy('priority')
            ->get()
            ->keyBy('priority');

        $highPriority = $taskStats->get('High');
        $mediumPriority = $taskStats->get('Medium');
        $lowPriority = $taskStats->get('Low');

        $allTasks = (object)[
            'past_due' => ($highPriority->past_due ?? 0) + ($mediumPriority->past_due ?? 0) + ($lowPriority->past_due ?? 0),
            'due_today' => ($highPriority->due_today ?? 0) + ($mediumPriority->due_today ?? 0) + ($lowPriority->due_today ?? 0),
            'two_weeks' => ($highPriority->two_weeks ?? 0) + ($mediumPriority->two_weeks ?? 0) + ($lowPriority->two_weeks ?? 0),
            'thirty_days' => ($highPriority->thirty_days ?? 0) + ($mediumPriority->thirty_days ?? 0) + ($lowPriority->thirty_days ?? 0),
        ];

        return [
            'highPriority' => $highPriority,
            'mediumPriority' => $mediumPriority,
            'lowPriority' => $lowPriority,
            'allTasks' => $allTasks,
        ];
    }

    public function tasks(Request $request, $priority, $period)
    {
        $this->checkAdmin();
        $today = Carbon::today();
        $twoWeeks = Carbon::today()->addDays(14);
        $thirtyDays = Carbon::today()->addDays(30);

        $query = ReservationTask::query()
            ->with([
                'reservation:id,customer_id',
                'reservation.customer:id,fname,lname,mname',
                'agent:id,fname,lname'
            ])
            ->where('is_completed',0)
            ->where('is_deleted',0)
            ->whereHas('reservation',function($q){
                $q->where('is_deleted',0);
            });

        if ($request->agent_id && $request->agent_id != -1) {
            $query->where('created_by',$request->agent_id);
        }

        if ($priority !== 'All') {
            $query->where('priority',$priority);
        }

        switch ($period) {

            case 'past_due':
                $query->whereDate('due_date','<',$today);
                break;

            case 'due_today':
                $query->whereDate('due_date',$today);
                break;

            case 'two_weeks':
                $query->whereDate('due_date','>',$today)->whereDate('due_date','<=',$twoWeeks);
                break;

            case 'thirty_days':
                $query->whereDate('due_date','>',$today)->whereDate('due_date','<=',$thirtyDays);
                break;
        }

        $tasks = $query->select(['id', 'reservation_id', 'task_name', 'due_date', 'priority', 'created_by' ])->orderBy('due_date')->get();
                    
        return response()->json($tasks);
    }
}