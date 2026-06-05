<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReservationTask;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Reservation;
use App\Models\PaidCommission;

class AgentDashboardController extends Controller
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

        return view('dashboards.agentDashboard', compact('highPriority'));
    }

    public function upcomingReservations(Request $request)
    {
        $userId = auth()->id();

        $startDate = Carbon::today()->toDateString();
        $endDate = Carbon::today()->addDays(90)->toDateString();

        $reservations = Reservation::query()
            ->with('customer')
            ->where('is_deleted', 0)
            ->where('agent_id', $userId)
            ->whereIn('status', ['Active', 'Paid in Full'])
            ->whereBetween('checkout_date', [$startDate, $endDate])
            ->orderBy('checkin_date')
            ->get();

        $chartData = [];
        $categories = [];

        foreach ($reservations as $index => $r) {

            $categories[] = $r->reservation_number;

            $chartData[] = [
                'x' => Carbon::parse($r->checkin_date)->timestamp * 1000,
                'x2' => Carbon::parse($r->checkout_date)->timestamp * 1000,
                'y' => $index,
            ];
        }

        return response()->json([
            'count' => $reservations->count(),
            'categories' => $categories,
            'chartData' => $chartData,
            'reservations' => $reservations->map(function ($r) {
                return [
                    'id' => $r->id,
                    'checkin_date' => $r->checkin_date,
                    'reservation_number' => $r->reservation_number,
                    'customer' => $r->customer
                        ? $r->customer->lname . ', ' . $r->customer->fname
                        : ''
                ];
            })
        ]);
    }

    public function totalSales()
    {
        $userId = auth()->id();

        $years = [
            now()->subYear()->year,
            now()->year,
            now()->addYear()->year,
        ];

        $data = [];

        foreach ($years as $year) {

            $totalSales = Reservation::query()
                ->where('is_deleted', 0)
                ->where('agent_id', $userId)
                ->whereIn('status', ['Active', 'Paid in Full'])
                ->whereBetween('checkin_date', [$year . '-01-01',$year . '-12-31'])
                ->sum('reservation_cost');

            $data[] = ['name' => (string) $year,'y' => (int) $totalSales ];
        }

        return response()->json($data);
    }

    public function recentCommissions(Request $request)
    {
        $range = $request->get('range', '30days');

        $endDate = Carbon::today();

        switch ($range) {
            case '30days':
                $startDate = Carbon::today()->subDays(30);
                break;

            case '90days':
                $startDate = Carbon::today()->subDays(90);
                break;

            case '180days':
                $startDate = Carbon::today()->subDays(180);
                break;

            case '1year':
                $startDate = Carbon::today()->subYear();
                break;

            case 'all':
                $startDate = null;
                break;

            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid range'
                ]);
        }

        $query = PaidCommission::query()
            ->select('paid_commissions.*','reservations.reservation_number','reservations.agency_commission',DB::raw("CONCAT(customers.lname, ', ', customers.fname) as customer_name"))
            ->join('reservations','reservations.id','=', 'paid_commissions.reservation_id')
            ->join('customers','customers.id','=','reservations.customer_id')
            ->where('paid_commissions.agent_id', auth()->id());

        if ($startDate) {
            $query->whereBetween('check_date', [$startDate->toDateString(),$endDate->toDateString()]);
        }

        $commissions = $query->orderBy('check_date')->get();

        return response()->json([
            'totalAgentCommission' => number_format(
                $commissions->sum('amount'),
                2,
                '.',
                ''
            ),

            'rows' => $commissions->map(function ($c) {
                return [
                    'check_date' => Carbon::parse($c->check_date)->format('m/d/Y'),
                    'reservation_number' => $c->reservation_number,
                    'customer_name' => $c->customer_name,
                    'check_number' => $c->check_number,
                    'agent_commission' => $c->amount,
                    'total_commission' => $c->agency_commission,
                ];
            })
        ]);
    }

}
