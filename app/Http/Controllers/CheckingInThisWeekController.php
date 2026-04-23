<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Reservation;

class CheckingInThisWeekController extends Controller
{
     public function index()
    {
        $userId = auth()->id();

        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $thisWeekReservations = Reservation::where('agent_id', $userId)
            ->whereBetween('checkin_date', [$startOfWeek, $endOfWeek])
            ->where('is_deleted',0)
            ->get();

        $startOfNextWeek = Carbon::now()->addWeek()->startOfWeek();
        $endOfNextWeek = Carbon::now()->addWeek()->endOfWeek();

        $nextWeekReservations = Reservation::where('agent_id', $userId)
            ->whereBetween('checkin_date', [$startOfNextWeek, $endOfNextWeek])
            ->where('is_deleted',0)
            ->get();

        return view('dashboards.checkingInThisWeek', compact(
            'thisWeekReservations',
            'nextWeekReservations'
        ));
    }

}
