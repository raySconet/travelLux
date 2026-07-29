<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CurrentChecksReportController extends Controller
{
    public function index()
    {
        return view('reports.currentChecksReport');
    }

    public function loadReport()
    {
        $user = Auth::user();

        $query = Reservation::with(['agent:id,fname,lname','customer:id,fname,lname'])
            ->whereIn('status', ['Paid in Full','Canceled w/ Insurance Payout','Canceled - Commission Protected'])
            ->where('non_commissionable', 0)
            ->where('agent_commission_received', 0)
            ->where('commission_received', 1)
            ->where('is_deleted', 0)
        ;

        if ($user->role != 1) {
            $query->where('agent_id', $user->id);
        }

        $reservations = $query
            ->orderBy('agent_id')
            ->orderBy('checkout_date')
            ->get()
        ;

        $agents = $reservations->groupBy('agent_id');

        return view('reports.partials.currentChecksTable', compact('agents'));
    }
}