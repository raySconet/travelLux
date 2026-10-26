<?php

namespace App\Http\Controllers;

use App\Models\PaidCommission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;

class CheckHistoryReportController extends Controller
{
    public function index()
    {
        return view('reports.checkHistoryReport');
    }

    public function loadReport(Request $request)
    {
        $dates = $request->validate([
            'beginDate' => ['nullable', 'date_format:Y-m-d'],
            'endDate' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:beginDate'],
        ]);
        $beginDate = $dates['beginDate'] ?? now()->subMonth()->toDateString();
        $endDate = $dates['endDate'] ?? now()->toDateString();

        $user = Auth::user();

        $agents = User::query()
            ->where('isDeleted', 0)
            ->where('id', '!=', 6680)
            ->whereHas('paidCommissions')
            ->when(!$user->isAdmin(), function ($query) use ($user) {
                $query->where('id', $user->id);
            })
            ->orderBy('lname')
            ->orderBy('fname')
            ->get()
        ;

        foreach ($agents as $agent) {

            $commissions = PaidCommission::with(['reservation.customer'])
                ->where('agent_id', $agent->id)
                ->whereBetween('check_date', [$beginDate, $endDate])
                ->orderBy('id')
                ->get()
            ;

            $agent->history = $commissions;

            $agent->totalPaid = $commissions->sum('amount');
            if ((int) $agent->id === 8) {
                $agent->agencyTotal = DB::table('reservations')
                    ->where('commission_received', 1)->where('agent_commission_received', 1)
                    ->whereIn('status', ['Paid in Full', 'Canceled w/ Insurance Payout', 'Canceled - Commission Protected'])
                    ->where('checkin_date', '>=', $beginDate)->where('checkout_date', '<=', $endDate)
                    ->where('agent_id', '!=', 6680)
                    ->selectRaw('SUM(agency_commission - agent_commission) as total')->value('total');
            }
        }

        $agents = $agents->filter(function ($agent) {
            return $agent->history->count() > 0;
        });

        return view('reports.partials.checkHistoryTable',compact('agents'));
    }

    public function undoPayment(Request $request)
    {
        DB::beginTransaction();

        try {

            PaidCommission::where('reservation_id', $request->reservationId)
                ->where('agent_id', $request->agentId)
                ->where('check_number', $request->checkNumber)
                ->delete()
            ;

            Reservation::where('id', $request->reservationId)
                ->where('agent_id', $request->agentId)
                ->update(['agent_commission_received' => 0])
            ;

            DB::commit();

            return response()->json([
                'success' => true
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to undo payment.'
            ],500);

        }
    }
}
