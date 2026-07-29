<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class VendorReportController extends Controller
{
    public function index()
    {
        return view('reports.vendorReport');
    }

    public function loadReport(Request $request)
    {
        $beginDate = $request->filled('beginDate') ? Carbon::parse($request->beginDate)->startOfDay() : now()->startOfMonth();
        $endDate = $request->filled('endDate') ? Carbon::parse($request->endDate)->endOfDay() : now()->endOfDay();

        $status = $request->status ?? '-1';

        $query = Reservation::with([
            'product:id,product_name',
            'destination:id,destination_name',
            'resort:id,resort_ship_name'
        ])
        ->where('is_deleted',0)
        ->whereBetween('checkin_date',[
            $beginDate->toDateString(),
            $endDate->toDateString()
        ]);

        if ($status != '-1') {
            $query->where('status',$status);
        } else {
            $query->whereIn('status',[
                'Active',
                'Canceled',
                'Paid in Full',
                'Canceled w/ Insurance Payout',
                'Canceled - Commission Protected'
            ]);
        }

        if (Auth::user()->role != 1) {
            $query->where('agent_id',Auth::id());
        }

        $reservations = $query
            ->orderBy('product_id')
            ->orderBy('destination_id')
            ->orderBy('resort_id')
            ->orderBy('reservation_number')
            ->get()
        ;

        $totalAgentCommission = $reservations->sum('agent_commission');

        $totalSales = $reservations->sum('reservation_cost');

        $vendors = $reservations->groupBy('product_id');

        return response()->view('reports.partials.vendorReportTable',compact('vendors','totalAgentCommission','totalSales'));
    }
}