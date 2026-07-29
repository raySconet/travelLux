<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\PaidCommission;
use App\Models\User;

class Report1099Controller extends Controller
{
    public function index()
    {
        $users = User::select('id','fname', 'lname' ,'email')->where('isDeleted',0)->get();
        return view('reports.1099Report', compact('users'));
    }

    public function loadReport(Request $request)
    {
        $beginDate = $request->beginDate ? Carbon::parse($request->beginDate)->startOfDay() : now()->subMonth()->startOfDay();

        $endDate = $request->endDate ? Carbon::parse($request->endDate)->endOfDay() : now()->endOfDay();

        $query = PaidCommission::select('agent_id', DB::raw('SUM(amount) as total_paid'))
            ->with('agent')
            ->whereBetween('check_date', [$beginDate, $endDate])
            ->groupBy('agent_id')
        ;

        if (!Auth::user()->isAdmin()) {
            $query->where('agent_id', Auth::id());
        }

        $agents = $query->get();

        if ($agents->isEmpty()) {

            return response()->json([
                'success' => false,
                'message' => 'No Data Found.'
            ]);
        }

        return response()->json([
            'success' => true,
            'totalPaid' => number_format($agents->sum('total_paid'), 2),
            'data' => $agents->map(function ($commission) {

                $agent = $commission->agent;

                return [
                    'agent' => $agent->fname.' '.$agent->lname,
                    'address' => trim($agent->first_address_line.' - '. $agent->city.' - '. $agent->state ),
                    'phone' => $agent->cell_phone_number,
                    'birth_date' => optional($agent->birth_date) ? Carbon::parse($agent->birth_date)->format('m-d-Y') : '',
                    'ssn' => $agent->ssn,
                    'ein' => $agent->ein,
                    'postal_code' => $agent->postal_code,
                    'commission_paid' => number_format($commission->total_paid,2)
                ];
            })
        ]);
    }
}
